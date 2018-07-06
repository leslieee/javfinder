<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$info->alt}</title>
    <link rel="stylesheet" href="/assets/public/css/core.css">
    <link rel="stylesheet" href="/assets/public/css/video-js.min.css">
    <script>
        window.HELP_IMPROVE_VIDEOJS = false;
    </script>
</head>

<body>
    <header id="header">
        <nav class="navbar navbar-default megamenu">
            <div class="container">
                <div class="col-md-2">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="/"><img src="https://cdnfd.me/v1/img/logo.png?1503498498" alt="Logo"></a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="navbar-form navbar-left search-form">
                        <div class="form-group">
                            <input id="search_input" type="text" class="form-control search-field search-key" placeholder="Movies Search" value="{$key}">
                        </div>
                        <button id="search_button" class="btn btn-default search-submit"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div class="col-md-4 text-right hidden-sm hidden-xs">
                </div>
            </div>
        </nav>
    </header>
    <!-- /header -->
    <div id="page" class="clearfix">
        <section id="primary">
            <div class="wrap-block">
                <div class="container">
                    <div class="bar-main-block clearfix">
                        <div class="row">
                            {if $key != ""}
                            <h2 class="text-left">Search: {$key}</h2>
                            {else}
                            <h2 class="text-left">{$info->alt}</h2>
                            {/if}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-10">
                            <video id="my-player" class="video-js vjs-default-skin vjs-big-play-centered" controls poster="{$info->getProxyLink()}" data-setup='{
                            "fluid": true,
                            "loop": "true",
                            "autoplay": true,
                            "preload": "auto"}'>
                            <source src="{$url}" type="video/mp4"></source>
                            </video>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                    <div class="bar-main-block clearfix">
                        <div class="row">
                            <h2>Same Porn Star</a></h2>
                        </div>
                    </div>
                    <div class="row">
                        {foreach $infos as $in}
                        <!--col-lg-2 col-md-3 col-sm-6 col-xs-6-->
                        <div class="col-md-3 col-sm-6 text-center main-item">
                            <div class="wrap-main-item">
                                <a class="main-thumb lazyload-img" href="/watch/{$in->data_id}">
                                    <i><img class="placeholder iswatched" src="{$in->getProxyLink()}"></i>
                                    <span class="bagde">HD</span>
                                    <span class="time-video">{$in->time_video}</span>
                                    <span class="views"><i class="fa fa-eye"></i>{$in->views}</span>
                                </a>
                                <h5><a href="/watch/{$in->data_id}" title="">{$in->alt}</a></h5>
                                <p>Star: <a href="">{$in->star}</a></p>
                            </div>
                        </div>
                        {/foreach}
                    </div>
                    <div class="bar-main-block">
                        <div class="row">
                            <div class="text-center">
                                <h2><a href="/search/{$info->star}" title="">View All Videos</a></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer id="footer">
        <div class="container">
            <div class="text-center"><b>Jav Free</b>, <b>Jav Streaming</b>, <b>Jav Uncensored</b>, <b>Jav Censored</b>, <b>Jav Online</b>
            </div>
        </div>
        <div class="copyright">
            <div class="container">
            </div>
        </div>
    </footer>
    <a href="#" class="to-top" style=""><i class="fa fa-long-arrow-up"></i></a>
    <script>
        document.getElementById('search_button').addEventListener('click', function(){
            var value = document.getElementById('search_input').value;
            window.location.href = "/search/" + value;
        },false);        
    </script>
    <script src="/assets/public/js/video.min.js"></script>
    <script src="/assets/public/js/videojs.hotkeys.min.js"></script>
    <script>
        videojs('my-player').ready(function() {
            this.hotkeys({
                volumeStep: 0.1,
                seekStep: 10,
                enableModifiersForNumbers: false
            });
        });
    </script>
</body>

</html>
