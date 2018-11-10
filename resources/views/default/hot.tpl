<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0b0b0b">
    <title>MekeLove Popular Asian Porn Movies, TOP JAV HOT on JavFinder | JAV FREE STREAMING ONLINE</title>
    <link rel="stylesheet" href="/assets/public/css/core.css">
</head>

<body>
    <header id="header">
        <nav class="navbar navbar-default megamenu">
            <div class="container">
                <div class="col-md-2">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="/"><img src="/assets/public/img/logo.png" alt="Logo"></a>
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
                    {if $new == false}
                    <div class="bar-main-block clearfix visible-xs">
                        <div class="row">
                            <div class="col-md-10">
                                <h2><a href="/randmode" title="">看腻了? 试试4万部随机模式</a></h2>
                            </div>
                        </div>
                    </div>
                    {/if}
                    <div class="bar-main-block clearfix">
                        <div class="row">
                            <div class="col-xs-7 col-sm-4 col-md-4 col-lg-4">
                                {if $key != ""}
                                <h2 class="text-left">Search: {$key}</h2>
                                {else}
                                <h2 class="text-left">Popular Porn</h2>
                                {/if}
                            </div>
                            <div class="hidden-xs col-xs-5 col-sm-5 col-md-4 col-lg-4">
                                {if $new == false}
                                <h2><a href="/randmode" title="">看腻了? 试试4万部随机模式</a></h2>
                                {/if}
                            </div>
                            {if $index}
                            <div class="col-xs-5 col-sm-3 col-md-4 col-lg-4">
                                <h5 class="text-right"><a href="/movie/page/1" title="">View All Videos</a></h5>
                            </div>
                            {/if}
                        </div>
                    </div>
                    <div class="row">
                        {foreach $infos as $info}
                        <!--col-lg-2 col-md-3 col-sm-6 col-xs-6-->
                        <div class="col-md-3 col-sm-6 text-center main-item">
                            <div class="wrap-main-item">
                                <a class="main-thumb lazyload-img" href="/watch/{$info->data_id}">
                                    <i><img class="placeholder iswatched" src="{$info->getProxyLink()}"></i>
                                    <span class="bagde">HD</span>
                                    <span class="time-video">{$info->time_video}</span>
                                    <span class="views"><i class="fa fa-eye"></i>{$info->views}</span>
                                </a>
                                <h5><a href="/watch/{$info->data_id}" title="">{$info->alt}</a></h5>
                                <p>Star: <a href="/search/{$info->star}">{$info->star}</a></p>
                            </div>
                        </div>
                        {/foreach}
                    </div>
                    <!-- pagination -->
                    <div class="bar-main-block">
                        <div class="row">
                            <div class="text-center">
                                {if $index}
                                        <h2><a href="/movie/page/1" title="">View All Videos</a></h2>
                                    
                                {else}
                                        <ul class="pagination">
                                        {if $infos->currentPage()==1}
                                        <li><a href="/movie/page/{$infos->currentPage()}"><i class="fa fa-angle-double-left"></i></a></li>
                                        {else}
                                        <li><a href="/movie/page/{$infos->currentPage()-1}"><i class="fa fa-angle-double-left"></i></a></li>
                                        {/if}
                                        {if $infos->currentPage()>2}
                                            <li><a href="/movie/page/{$infos->currentPage()-2}">{$infos->currentPage()-2}</a></li>
                                            <li><a href="/movie/page/{$infos->currentPage()-1}">{$infos->currentPage()-1}</a></li>
                                        {/if}
                                        <li class="active"><a href="/movie/page/{$infos->currentPage()}">{$infos->currentPage()}</a></li>
                                        {if $infos->hasMorePages()}
                                            <li><a href="/movie/page/{$infos->currentPage()+1}">{$infos->currentPage()+1}</a></li>
                                            <li><a href="/movie/page/{$infos->currentPage()+2}">{$infos->currentPage()+2}</a></li>
                                        {/if}
                                        {if $infos->hasMorePages()}
                                            <li><a href="/movie/page/{$infos->currentPage()+1}"><i class="fa fa-angle-double-right"></i></a></li>
                                        {else}
                                            <li><a href="/movie/page/{$infos->currentPage()}"><i class="fa fa-angle-double-right"></i></a></li>
                                        {/if}
                                    </ul>
                                {/if}
                            </div>
                        </div>
                    </div>
                    <!-- /pagination -->
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
    <a href="#" class="to-top" style="bottom: 50px;"><i class="fa fa-long-arrow-up"></i></a>
    <script>
        document.getElementById('search_button').addEventListener('click', function(){
            var value = document.getElementById('search_input').value;
            window.location.href = "/search/" + value;
        },false);        
    </script>
</body>

</html>
