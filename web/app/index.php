<?php
session_start();
if(!isset($_SESSION['username'])){
    header('Location: /projeto-0/web/');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>App</title>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script defer src='script.js'></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="app">
        <header>App</header>
        <div class="input_new_url">
        </div>
        <input type="button" value="Refresh" @click.prevent="getUrl()" class="refresh">
        <div class="main_content">
            <div class="flex pointer">
            
                <div class="urlName" v-show='!showInput' @click='showUrlInput(true)'><span class=" icon icon-plus"></span>Add url</div>
                <div class="urlInput" v-if='showInput'>
                    <input type="text" @blur='showUrlInput(false)' ref='urlInput' id='urlInput' v-model="new_url" 
                    @keydown.enter="()=>{if(testRegex(new_url)){addUrl();showUrlInput(false);new_url=''} else{new_url='Invalid Url!';}}">
                </div>
            </div>
            <div v-for="url of urlsList" class='content'>
                <a :href="'#' + url['url_id']" @click="showData($event,url['url_checked'],url['url_id'])" :class="{'pointer': url['url_checked']=== '1'}" >
                    <div class="flex" :id="url['url_id']" >
                        <div class="urlName">Url: {{url['url_url']}}</div>
                        <div class="verifiedSign" v-show="url['url_checked']=== '1'">verified</div>
                        <div class="unverifiedSign" v-show="url['url_checked']=== '0'">unverified</div>
                        <div class="statusCodeSign" v-show="url['url_checked']=== '1'" 
                            :style="{'background-color': setStatusColor(url['url_code'])}" >status:{{url['url_code']}}</div>
                    </div>
                </a>
                <div class="flex2" v-html="'Header: <br/>' + url['url_header']" v-show="menuData === url['url_id']"></div>
                <div class="flex2" v-show="menuData === url['url_id']">Body:<br/> {{url['url_body']}}</div>
            </div>
        </div>
    </div>
</body>
</html>