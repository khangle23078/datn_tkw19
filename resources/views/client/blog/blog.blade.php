@extends('client.layout.index')
@section('client')
    {{-- <section class="middle">
        <div class="container">


            <div class="row">
                @foreach ($company as $item)
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 hover-div1">
                        <a href="{{ route('detail.company', $item->id) }}">
                            <div class="_blog_wrap">
                                <div class="_blog_thumb mb-2 p-2">
                                    <a href="{{ route('detail.company', $item->id) }}" class="d-block"><img
                                            src="{{ $item->logo }}" class="img-fluid rounded" alt="" /></a>
                                </div>
                                <div class="_blog_caption">
                                    <span class="text-muted"> {{ $item->created_at->format('d-m-Y') }}</span>
                                    <h5 class="bl_title lh-1 maxTextCompany"><a
                                            href="{{ route('detail.company', $item->id) }}">{{ $item->name }}</a></h5>

                                    <div class="maxText">
                                        <p>{!! $item->desceibe !!}</p>
                                    </div>
                                    <a href="{{ route('detail.company', $item->id) }}" class="text-dark fs-sm">Continue
                                        Reading..</a>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

            </div>



        </div>
    </section> --}}
    <main class="main">

        <section class="section-box">
            <div class="breacrumb-cover " style="">
                <div class="container">
                    <div class="row d-grid d-md-flex gap-30 gap-lg-0 align-items-end">
                        <div class="col-lg-6">
                            <h2 class="mb-0">Blog</h2>
                            <div class="banner-subtitle font-lg color-text-paragraph-2 mt-10">Get the latest news, updates
                                and tips
                            </div>
                        </div>
                        <div class="col-lg-6 text-lg-end">
                            <ul class="breadcrumbs list-unstyled">
                                <!-- Breadcrumb NavXT 7.1.0 -->
                                <li class="home"><span property="itemListElement" typeof="ListItem"><a property="item"
                                            typeof="WebPage" title="Go to Jobbox."
                                            href="https://jthemes.com/themes/wp/jobbox" class="home home-icon"><span
                                                property="name">Jobbox</span></a>
                                        <meta property="position" content="1">
                                    </span></li>
                                <li class="post-root post post-post current-item"><span property="itemListElement"
                                        typeof="ListItem"><span property="name"
                                            class="post-root post post-post current-item">Blog</span>
                                        <meta property="url" content="index.html">
                                        <meta property="position" content="2">
                                    </span></li>
                                <!-- <li><a class="home-icon" href="#">Home</a></li> -->

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="trending-news section-box mt-50">
            <div class="container">
                <div class="row">
                    @foreach ($news as $news)
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                            <div class="card-grid-5">
                                <div class="card-grid-5 hover-up"
                                    style="background-image: url('{{ $news->new_image }}'); background-position:center center; background-repeat:no-repeat">
                                    <a href="{{ route('detail.blog', $news->id) }}">
                                        <div class="box-cover-img">
                                            <div class="content-bottom">
                                                <h3 class="color-white mb-20">{{ $news->title }}</h3>
                                                <div class="author d-flex align-items-center">
                                                    <img alt=''
                                                        src='../wp-content/uploads/2022/09/user10-150x150.png'
                                                        srcset='https://jthemes.com/themes/wp/jobbox/wp-content/uploads/2022/09/user10-150x150.png 2x'
                                                        class='avatar avatar-64 photo img-rounded' height='35'
                                                        width='35' loading='lazy' decoding='async' /> <span
                                                        class="color-white font-sm mr-25">ADMIN</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>
            </div>
        </section>


    </main>
@endsection
