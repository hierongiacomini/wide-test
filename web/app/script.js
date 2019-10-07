var app = new Vue({
    el: '#app',
    data:{
        new_url:'',
        urlsList:'',
        created_Url:false,
        showInput:false,
        menuData:0,
    },
    updated(){
        if(this.showInput===true){
            this.$refs['urlInput'].focus()
        }
    },
    created() {
        this.getUrl()
        //this.interval = setInterval(() => this.getUrl(), 30000);      
    },
    methods:{
        addUrl(){
            fetch('registerUrl.php',{
                method: 'post',
                body: JSON.stringify({url:this.new_url})
            })
            .then(res=>{
                if (res.status == 201){this.created_Url = true;}
                else{console.log(res.json());}
            }).then(()=>this.getUrl());
        },
        getUrl(){
            fetch('getUrls.php')
            .then(res=>{
                if (res.status==200){return res}
                else{this.urlsList = 'none';}
            }).then(async obj=>{
                this.urlsList = '';
                let list = await obj.json();
                this.urlsList = list['message'].reverse();
            })
        },
        setStatusColor(value){
            if(value>=200 && value<300){return '#51b1d6';}
            else if(value>=300 && value<400){return '#d6bc51';}
            else if(value>=400){return '#d65151';}
            else{return '#d651d4';}
        },
        showUrlInput(value){
            this.showInput=value;
            this.new_url = '';
        },
        testRegex(value){
            return /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/gm.test(value);
        },
        showData(event,value,id){
            if (value==='1'){
                if (this.menuData!==0){
                    this.menuData=0;
                }
                else{
                    this.menuData = id;
                }
                
            }
            else{
                event.preventDefault();
            }
        }
    }
});