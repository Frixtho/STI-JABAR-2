<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AssetHistory; // <-- Pastikan model AssetHistory di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AddUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                ->orWhere('email', 'ILIKE', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role != 'All' && $request->role != '') {
            $query->where('role', $request->role); 
        }

        if ($request->has('status') && $request->status != 'All' && $request->status != '') {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(10)->withQueryString();

        return view('manageUser', compact('users'));
    }
    
    public function create()
    {
        return view('addUser'); 
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'nip'        => 'required|string',
            'department' => 'required|string',
            'role'       => 'required|string',
            'status'     => 'required|string',
            'password'   => 'required|string|min:8',
        ]);

        $validated['password'] = Hash::make($request->password);
        
        $user = User::create($validated);

        // Catat riwayat penambahan user
        AssetHistory::create([
            'user_id' => auth()->id(),
            'action' => 'TAMBAH',
            'description' => "Menambahkan pengguna baru: {$user->name} ({$user->email}).",
        ]);

        return redirect()->route('manage-user')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('addUser', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'nip'        => 'required|string',
            'department' => 'required|string',
            'role'       => 'required|string',
            'status'     => 'required|string',
            'password'   => 'nullable|string|min:8', 
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        // Catat riwayat pembaruan user
        AssetHistory::create([
            'user_id' => auth()->id(),
            'action' => 'UBAH',
            'description' => "Memperbarui profil pengguna: {$user->name}.",
        ]);

        return redirect()->route('manage-user')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $userName = $user->name;
        $user->delete();

        // Catat riwayat penghapusan user
        AssetHistory::create([
            'user_id' => auth()->id(),
            'action' => 'HAPUS',
            'description' => "Menghapus pengguna: {$userName}.",
        ]);

        return redirect()->route('manage-user')->with('success', "Pengguna {$userName} berhasil dihapus.");
    }

    public function importForm()
    {
        return view('userImport');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'files'   => ['required', 'array'],
            'files.*' => ['required', 'file', 'max:10240'],
        ]);

        if (!$request->hasFile('files')) {
            return back()->with('error', 'Tidak ada file yang diunggah.');
        }

        $skippedReasons = [];
        $successCount = 0;

        foreach ($request->file('files') as $file) {
            try {
                $extension = strtolower($file->getClientOriginalExtension());
                $dataRows = [];

                if (!in_array($extension, ['xlsx', 'csv', 'txt'])) {
                    $skippedReasons[] = "File " . $file->getClientOriginalName() . " dilewati: Format harus .xlsx atau .csv";
                    continue;
                }

                if (in_array($extension, ['csv', 'txt'])) {
                    $handle = fopen($file->getRealPath(), 'r');
                    $sampleLine = fgets($handle);
                    rewind($handle);
                    $delimiter = (substr_count($sampleLine, ';') > substr_count($sampleLine, ',')) ? ';' : ',';

                    while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                        $dataRows[] = $row;
                    }
                    fclose($handle);
                } 
                elseif ($extension === 'xlsx') {
                    $dataRows = $this->parseXlsxNative($file->getRealPath());
                }

                if (empty($dataRows)) {
                    $skippedReasons[] = "File " . $file->getClientOriginalName() . " kosong atau gagal dibaca.";
                    continue;
                }

                $rowIndex = 0;
                foreach ($dataRows as $row) {
                    $rowIndex++;
                    if ($rowIndex == 1) continue; 

                    if (count($row) < 2 || empty(trim($row[1] ?? ''))) {
                        continue; 
                    }

                    $namaUser = trim($row[1]);
                    $keterangan = trim($row[2] ?? '');

                    $emailSlug = Str::slug($namaUser, '.');
                    $email = $emailSlug . '_' . $rowIndex . '@pln.co.id';

                    $userExists = User::where('name', $namaUser)->first();
                    if ($userExists) {
                        $skippedReasons[] = "Baris ke-{$rowIndex} (" . $file->getClientOriginalName() . "): User '{$namaUser}' sudah ada.";
                        continue;
                    }

                    User::create([
                        'name'       => $namaUser,
                        'email'      => $email,
                        'password'   => Hash::make('pln12345'),
                        'role'       => 'Staff',                
                        'department' => $keterangan,            
                        'status'     => 'Aktif', 
                    ]);

                    $successCount++;
                }

            } catch (\Exception $e) {
                $skippedReasons[] = "Gagal memproses " . $file->getClientOriginalName() . ": " . $e->getMessage();
            }
        }

        // Catat riwayat import user JIKA ada data yang berhasil masuk
        if ($successCount > 0) {
            AssetHistory::create([
                'user_id' => auth()->id(),
                'action' => 'TAMBAH',
                'description' => "Melakukan impor massal daftar pengguna ({$successCount} akun berhasil).",
            ]);
        }

        $message = "Berhasil mengimpor {$successCount} data User.";
        if (count($skippedReasons) > 0) {
            session()->flash('import_skipped_reasons', $skippedReasons);
        }

        return redirect()->route('manage-user')->with('success', $message);
    }

    private function parseXlsxNative($filePath)
    {
        $zip = new \ZipArchive;
        if ($zip->open($filePath) === true) {
            $sharedStrings = [];

            if (($ssXml = $zip->getFromName('xl/sharedStrings.xml')) !== false) {
                $ss = simplexml_load_string($ssXml);
                foreach ($ss->si as $val) {
                    $text = '';
                    if (isset($val->t)) {
                        $text = (string)$val->t;
                    } elseif (isset($val->r)) {
                        foreach ($val->r as $r) { $text .= (string)$r->t; }
                    }
                    $sharedStrings[] = $text;
                }
            }

            $sheet1Xml = $zip->getFromName('xl/worksheets/sheet1.xml');
            if (!$sheet1Xml) {
                $zip->close();
                return [];
            }

            $sheet1 = simplexml_load_string($sheet1Xml);
            $rows = [];

            foreach ($sheet1->sheetData->row as $row) {
                $rowData = [];
                foreach ($row->c as $c) {
                    $r = (string)$c['r']; 
                    $colAlpha = preg_replace('/[0-9]/', '', $r);
                    $colIndex = 0;
                    for ($i = 0; $i < strlen($colAlpha); $i++) {
                        $colIndex = $colIndex * 26 + (ord($colAlpha[$i]) - 64);
                    }
                    $colIndex -= 1;

                    $val = (string)$c->v;
                    if (isset($c['t']) && (string)$c['t'] == 's') {
                        $val = $sharedStrings[(int)$val] ?? '';
                    } elseif (isset($c['t']) && (string)$c['t'] == 'inlineStr') {
                        $val = (string)$c->is->t;
                    }
                    $rowData[$colIndex] = trim($val);
                }

                $maxKey = empty($rowData) ? -1 : max(array_keys($rowData));
                $normalizedRow = [];
                for ($i = 0; $i <= $maxKey; $i++) {
                    $normalizedRow[] = $rowData[$i] ?? '';
                }
                $rows[] = $normalizedRow;
            }
            $zip->close();
            return $rows;
        }
        return [];
    }
}