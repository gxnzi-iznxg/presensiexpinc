@if ($histori->isEmpty())
    <div class="alert alert-warning text-center">Data tidak ditemukan untuk bulan dan tahun yang dipilih.</div>
@else
    <ul class="listview image-listview">

        @foreach($histori as $d)
            <li>
                <div class="item">
                    @php
                        $path = Storage::url('/public/uploads/absensi/' . $d->foto_in);
                    @endphp
                    <img src="{{ url($path) }}" alt="image" class="image">
                    <div class="in">
                        <div>
                            <b>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</b><br>
                        </div>
                        <span class="badge {{ $d->jam_in < '07:00:00' ? 'bg-success' : 'bg-danger' }}">{{ $d->jam_in }}</span>
                        <span class="badge bg-primary">{{ $d->jam_out ?? '-' }}</span>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
@endif
