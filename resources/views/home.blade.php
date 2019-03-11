@extends('shared.layout')
@section('title', 'Depan')
@section('content')

<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        @foreach ($slides as $slide)
        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
            <img class="d-block w-100" style="height: 600px; object-fit: cover; filter: brightness(70%)" src="{{ route('slide.image', $slide) }}" alt="{{ $slide->name }}">
            <div class="carousel-caption d-none d-md-block">
                <h5> {{ $slide->name }} </h5>
                <p> {{ $slide->description }} </p>
            </div>
        </div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<div id="app">
    <div class="my-3 container">
        <div>
            <home/>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-7 mb-5">
                <guest-map/>
            </div>
        
            <div class="col-sm-12 col-md-5">
                <h1> Kabupaten Bengkayang </h1>
                <p>
                    Kabupaten Bengkayang adalah salah satu kabupaten di provinsi Kalimantan Barat, Indonesia. Sebelumnya merupakan pemekaran dari Kabupaten Sambas yang karena adanya Undang-undang Otonomi Daerah dimekarkan menjadi 3 daerah otonom yang terpisah, yaitu Kabupaten Sambas, Kabupaten Bengkayang dan Kota Singkawang. Terletak di bagian utara Kalimantan Barat, Kabupaten Bengkayang berbatasan langsung dengan Sarawak, Malaysia.
                </p>

                <p>
                    Pemerintah Kabupaten Bengkayang terbentuk berdasarkan Undang-undang Nomor 10 tahun 1999 tentang pembentukan Daerah Tingkat II Bengkayang, secara resmi mulai tanggal 20 April 1999, Kabupaten Bengkayang terpisah dari Kabupaten Sambas. Selanjutnya, pada tanggal 27 April 1999, Menteri Dalam Negeri dan Otonomi Daerah mengangkat Bupati Bengkayang pertama yang dijabat oleh Drs. Jacobus Luna. Pada waktu itu, wilayah Kabupaten Bengkayang ini meliputi 10 kecamatan.
                </p>

                <p>
                    Bengkayang memiliki sektor pariwisata memegang peranan penting dalam perekonomian daerah ini. Pemerintah Kabupaten Bengkayang telah melakukan promosi melalui media masa seperti surat kabar, namun metode tersebut belum cukup untuk menginformasikan kepariwisataan secara meluas kepada wisatawan Lokal maupun Asing. Pariwisata adalah perjalanan yang dilakukan oleh seseorang dalam jangka waktu tertentu dari suatu tempat ke tempat lain dengan melakukan perencanaan sebelumnya, tujuannya untuk rekreasi atau untuk suatu kepentingan sehingga keinginannya dapat terpenuhi.
                </p>

                <p>
                    Pariwisata merupakan salah satu aset yang sangat potensial untuk menambah pendapatan daerah. Dengan adanya pariwisata memiliki dampak positif bagi penduduk antara lain: menambah penghasilan dengan cara berdagang dan  masyarakat dapat membuka jasa pemotretan untuk wisatawan yang berkunjung. Ada beberapa jenis pariwisata di Kabupaten Bengkayang  antara lain : wisata pantai, wisata pulau, wisata alam, wisata budaya dan wisata sejarah.
                </p>
            </div>
        </div>
    </div>
</div>

@javascript('gmap_config', config('gmap_config'))
@javascript('points', $points)
@endsection