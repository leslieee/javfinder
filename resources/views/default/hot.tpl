<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Popular Asian Porn Movies, TOP JAV HOT on JavFinder | JAV FREE STREAMING ONLINE</title>
    <link rel="stylesheet" href="/assets/public/css/core.css">
</head>

<body>
    <header id="header">
        <nav class="navbar navbar-default megamenu">
            <div class="container">
                <div class="col-md-2">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <!--<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        </button>-->
                        <a class="navbar-brand" href="/"><img src="https://cdnfd.me/v1/img/logo.png?1503498498" alt="Logo"></a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="navbar-form navbar-left search-form">
                        <div class="form-group">
                            <input type="text" class="form-control search-field search-key" placeholder="Movies Search">
                        </div>
                        <button class="btn btn-default search-submit"><i class="fa fa-search"></i></button>
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
                            <div class="col-md-8 col-xs-8">
                                <h2 class="text-left">Popular Porn Videos</h2>
                            </div>
                            {if $index}
                            <div class="col-md-4 col-xs-4">
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
                                    <i><img class="placeholder iswatched" src="{$info->data_src}"></i>
                                    <span class="bagde">HD</span>
                                    <span class="time-video">{$info->time_video}</span>
                                    <span class="views"><i class="fa fa-eye"></i>{$info->views}</span>
                                </a>
                                <h5><a href="/watch/{$info->data_id}" title="">{$info->alt}</a></h5>
                                <p>Star: <a href="">{$info->star}</a></p>
                            </div>
                        </div>
                        {/foreach}
                    </div>
                    <!-- pagination -->
                    <div class="row">
                        <div class="text-center">
                            {if $index}
                                <div class="bar-main-block">
                                    <h2><a href="/movie/page/1" title="">View All Videos</a></h2>
                                </div>
                                
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
    <a href="#" class="to-top" style=""><i class="fa fa-long-arrow-up"></i></a>
</body>

</html>