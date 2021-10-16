let upload_status = 1; 
let files_input;
let resize_width,resize_height;
let alert_box;
window.addEventListener('DOMContentLoaded', function(){

    alert_box   = document.querySelector('.alert-box');

    files_input  = document.getElementById('files');
    files_input.onchange = function(a){
        let obj           = this.files;
        let objKey        = Object.keys(obj);
        let filearr       = [];
        for(let i= 0; i < objKey.length; i++){
            filearr[i] = obj[objKey[i]];
        }
        let allowed_types = ['application/x-zip-compressed','image/gif','image/png','image/jpeg'];
        let text_to_label = '';
        if(filearr == ""){
            upload_status = 0;
        } 
        filearr.forEach(function(item){
            if(allowed_types.indexOf(item.type) !== -1){
                text_to_label += item.name+',';
            }else{
                show_alert('This kind of file and type is not allowed: '+item.name,5000);
                text_to_label = 'This kind of file and type is not allowed: '+item.name;
                upload_status = 0;
                return false;
            }
        });
    
        if(text_to_label.length > 70){
            if(text_to_label.indexOf('not allowed') !== -1){
                upload_status = 0;
                document.querySelector('.box .title span').innerHTML = 'Invalid file has given';
            } else{
                upload_status = 1;
                document.querySelector('.box .title span').innerHTML = '';
                document.querySelector('.box .title span').innerHTML = text_to_label.substr(0,70)+'...';
            }
           
        }else{
            if(text_to_label == ""){
                upload_status = 0;
                document.querySelector('.box .title span').innerHTML = '';
                document.querySelector('.box .title span').innerHTML = 'Select your file(s)';
            }else{
                if(text_to_label.indexOf('not allowed') !== -1){
                    upload_status = 0;
                    document.querySelector('.box .title span').innerHTML = 'Invalid file has given';
                } else{
                    upload_status = 1;
                }
                document.querySelector('.box .title span').innerHTML = '';
                document.querySelector('.box .title span').innerHTML = text_to_label;
            }
        }
    
    };


     resize_width  = document.querySelector('#width');
     resize_height = document.querySelector('#height');


     resize_width.addEventListener('focusout',function(){
        if(this.value != ""){

            if(isNaN(this.value)){
                show_alert('Only numbers can be given!',3000);
                this.value = '';
            }else if(this.value <= 0){
                show_alert('Width must be greater than 0',3000);
                this.value = '';
            }
        }
    });

    resize_height.addEventListener('focusout',function(){
        if(this.value != ""){

            if(isNaN(this.value)){
                show_alert('Only numbers can be given!',3000);
                this.value = '';
            }else if(this.value <= 0){
                show_alert('Height must be greater than 0',3000);         
                this.value = '';  
            }
        }
    });

});

function show_alert(text,time){
    alert_box.innerHTML = text;
    alert_box.style.opacity = '1';
    alert_box.style.visibility = 'visible';
    setTimeout(function(){  alert_box.style.opacity = '0';
    alert_box.style.visibility = 'hidden';},time);
}

function process_upload(){

    if(upload_status){
        let form          = document.getElementById("upform");
        let postdata      =  new FormData(form);
        if(files_input.value !== ""){
            
            if(resize_width.value <= 0|| resize_height.value <= 0){
                show_alert('Width and height dimensions must be greater than 0',5000);
            }else{
                let btn    = document.querySelector('#upform button');
                let title  = document.querySelector('.box .title span');
                let result = document.querySelector('.box .result');
                btn.disabled = true;
                btn.style.background = '#EBEBE4';
                btn.innerHTML = 'Resizing..';
    
                let xhr = new XMLHttpRequest();
                xhr.open('post','api/resize_image.php',true);
                xhr.onload = function(){
                    if(xhr.readyState == 4){
                        var pj = JSON.parse(this.response);
                        btn.disabled = false;
                        btn.style.background = 'linear-gradient(to right,#3582fd,#0866ff)';
                        btn.innerHTML = 'Resize Images';
                        files_input.value = "";
                        title.innerHTML = 'Select your file(s)';
                        upload_status   = 1;

                        if(pj.resized_count > 0){
                    
                            result.innerHTML = '';
                            let items = '';
                            pj.resized.forEach(function(item){

                                var image_div = document.createElement('div');
                                image_div.className = 'image';

                                items += item+',';

                                var a = document.createElement('a');
                                a.href     = 'tmp/resized/'+item;
                                a.download = 'tmp/resized/'+item;

                                var img = document.createElement('img');
                                img.src = 'tmp/resized/'+item;

                                a.appendChild(img);
                                image_div.appendChild(a);
                                result.appendChild(image_div);

                            });
                            var text = document.createElement('div');
                            text.className = 'text';
                            text.insertAdjacentHTML('beforeend',pj.resized_count+' images have been resized to '+resize_width.value+'x'+resize_height.value+' ,<span onclick=\'download_as_zip(\"'+items+'\")\'> you can also download all of them in a zip file</span>');
                            result.insertAdjacentElement('afterbegin',text);
                       
                            result.style.display = 'flex';
                            result.style.width = '100%';
                            result.style.opacity = '1';
                            
                        }else{
                            if(pj.size_error){
                                show_alert('Maximum size can not be over 50 MB',5000);
                            }else if(pj.empty_error){
                                show_alert('Empty fields!',5000);
                            }else if(pj.type_error){
                                show_alert('Invalid file type has given!',5000);
                            }else{
                                show_alert('Something is wrong,could not resize the images',5000);
                            }                         
                        }

                    }
                }
                xhr.send(postdata);
            }

            
        }else{
            show_alert('You have to select at least one image or zip file to resize!',5000);
        }
    }else{
        show_alert("An error occured!",5000);
        return false;
    }
}

function download_as_zip(files){

    let postfields = new FormData();
    postfields.append('files',files);


    let xhr = new XMLHttpRequest();
    
    xhr.open('post','api/downloadZip.php',true);
    xhr.onload = function(){
        if(xhr.readyState == 4){
            var p = JSON.parse(this.response);
            if(p.download_url){
                
                let iframe = document.createElement('iframe');
                iframe.style.display = "none";
                iframe.src = "tmp/archives/"+p.download_url;
                document.body.appendChild(iframe);

                setTimeout(function(){
                    delete_zip(p.download_url);
                },5000);
            }else{
                show_alert('Something is wrong,either you have already downloaded or server-sided issue!',5000);
            }
        }
    }
    xhr.send(postfields);
}

function delete_zip(file){
    let data = new FormData();
    data.append('file',file);
    let xhr = new XMLHttpRequest();
    xhr.open('post','api/deleteZip.php',true);
    xhr.send(data);
}