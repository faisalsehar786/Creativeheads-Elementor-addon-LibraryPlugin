function CHFSFunction(){
jQuery('#add_CHFS_Custom_libray_modal').addClass('CHFSshow');
jQuery('#add_CHFS_Custom_libray_modal').css('display','block');
}

function CHFSFunctionhide(){
jQuery('#add_CHFS_Custom_libray_modal').removeClass('CHFSshow');
jQuery('#add_CHFS_Custom_libray_modal').css('display','none');
}


function CHFSFunction_view(){
jQuery('#add_CHFS_Custom_libray_modal_view').addClass('CHFSshow');
jQuery('#add_CHFS_Custom_libray_modal_view').css('display','block');
}

function CHFSFunctionhide_view(){
jQuery('#add_CHFS_Custom_libray_modal_view').removeClass('CHFSshow');
jQuery('#add_CHFS_Custom_libray_modal_view').css('display','none');
}



function loadMoreDataCHFS(paginate=0,category='',ispro='',search='') {

//console.log(paginate)
let commandUrl=`${ajax_object.CHFS_api_url}/api/getTemplates?cat=${category}&ispro=${ispro}&searchTempTitle=${search}&page=${paginate}`;

jQuery.ajax({
url:commandUrl,
type: 'GET',  
async: true,
crossDomain: true,
headers: {
"authorization": "Bearer "+ajax_object.CHFS_TOKEN,
"accept": "application/json",
"cache-control": "no-cache",
},
datatype: 'json',
cache: true,
beforeSend: function() {
jQuery('.loadmore_CHFS_button').text('Loading...');
}
})
.done(function(res) {

	console.log(res)
if(res.data.length == 0) {

jQuery('.loadmore_CHFS_button').text('No More Templates...');
return;
} else {

let temp='';


res.data.map(function(key, index) {

    let cat='';
	if(key.category){
    cat=key.category.name;
	}else{

    cat='not found';
	}

	    procheck='';

        if (key.is_pro=='yes') {
            procheck="Pro";
        }else{

           procheck="Not Pro"; 
        }
  temp+=`<div class="CHFScol-lg-3 CHFSmy-2">
<div class="CHFScard CHFSshadow-lg">
  <img class="CHFScard-img-top CHFSimg-fluid " src="${ajax_object.CHFS_API_File_Path}/public/tempimages/${key.img}" alt="Card image cap" style="height:150px;">
  <div class="CHFScard-body">
   
     
     <span class="CHFScHFSbadge CHFScHFSbadge-danger CHFSfloat-right">${procheck}</span>

    <p style="font-size:13px;">${key.name}</p>

     <strong class="CHFStext-danger"style="font-size:14px;">${cat}</strong>
     <br>
    <button CHFScHFS_import_link="${ajax_object.CHFS_API_File_Path}/public/tempjson/${key.file}" class="btn CHFScHFSbtn-success CHFScHFSbtn-sm" onclick=CHFScHFS_import(this)>import</button>
    <button CHFScHFS_view_link="${ajax_object.CHFS_API_File_Path}/public/tempimages/${key.img}" class="btn CHFScHFSbtn-warning CHFScHFSbtn-sm" onclick=CHFScHFS_view(this)>view</button>
  </div>
</div>
    </div> `;
});



jQuery('.loadmore_CHFS_button').text('Load More');

jQuery('#loadmore_CHFS').append(temp);	


}
})
.fail(function(jqXHR, ajaxOptions, thrownError) {
alert('Something went wrong.');
});
}
    
function CHFScHFS_import(template){



let tempUrlImp=template.getAttribute("CHFScHFS_import_link");
jQuery.ajax({
url:ajax_object.ajaxurl,
type: 'POST',  
datatype:'json',
data:{tempUrlImp:tempUrlImp,action:'frontend_action_without_file_chfs'},
cache: true,
beforeSend: function() {
template.innerText='importing...';
}
})
.done(function(res) {

 let reciveObj=JSON.parse(res);
if (reciveObj.status==200) {
template.innerText='imported';	
alert('This Template imported Successfully.You Check In Elementor Saved Templates...!')
}else{

	template.innerText='Error';	
}
  
  })
.fail(function(jqXHR, ajaxOptions, thrownError) {
alert('Something went wrong.');
});

	  
}

function CHFScHFS_view(view){

jQuery('.viewtemp').attr("src",view.getAttribute("CHFScHFS_view_link"));
CHFSFunction_view();	  
}


function CHFSFunctionLoadmore(){


let CFSH_CATGORY= jQuery('.CFSH_CATGORY').val();
let CFSH_IS_PRO= jQuery('.CFSH_IS_PRO').val();
let searchCFSH= jQuery('.searchCFSH').val();


let loadmchfs=jQuery('.loadmore_CHFS_button').attr('data-loadMoreCHFS');
let paginate =loadmchfs;
loadMoreDataCHFS(paginate,CFSH_CATGORY,CFSH_IS_PRO,searchCFSH);
jQuery('.loadmore_CHFS_button').attr('data-loadMoreCHFS',Number(paginate)+1);

}

loadMoreDataCHFS(paginate=1,category='all',ispro='all',search='');

function CFSH_CATGORY(ish){
  let CFSH_CATGORY= jQuery('.CFSH_CATGORY').val();
 let CFSH_IS_PRO= jQuery('.CFSH_IS_PRO').val();
 let searchCFSH= jQuery('.searchCFSH').val();
 jQuery('.loadmore_CHFS_button').attr('data-loadMoreCHFS',2);
jQuery('#loadmore_CHFS').html('');
   loadMoreDataCHFS(paginate=1,CFSH_CATGORY,CFSH_IS_PRO,searchCFSH);
}

function CFSH_IS_PRO(ish){
  let CFSH_CATGORY= jQuery('.CFSH_CATGORY').val();
 let CFSH_IS_PRO= jQuery('.CFSH_IS_PRO').val();
 let searchCFSH= jQuery('.searchCFSH').val();
 jQuery('.loadmore_CHFS_button').attr('data-loadMoreCHFS',2);
jQuery('#loadmore_CHFS').html('');
   loadMoreDataCHFS(paginate=1,CFSH_CATGORY,CFSH_IS_PRO,searchCFSH);
}

function searchCFSH(ish){
  let CFSH_CATGORY= jQuery('.CFSH_CATGORY').val();
 let CFSH_IS_PRO= jQuery('.CFSH_IS_PRO').val();
 let searchCFSH= jQuery('.searchCFSH').val();
 jQuery('.loadmore_CHFS_button').attr('data-loadMoreCHFS',2);
jQuery('#loadmore_CHFS').html(''); 

   loadMoreDataCHFS(paginate=1,CFSH_CATGORY,CFSH_IS_PRO,searchCFSH);
}



