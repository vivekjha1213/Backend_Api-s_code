$.ajax({
"async": true,
"crossDomain": true,
"url": "http://he.universe-telecom.com/he/api/jw/v1/get?serviceid=227",
"method": "GET",
"dataType": "jsonp",
"headers": {
"cache-control": "no-cache",
"Content-Type": "application/x-www-form-urlencoded"
}
}).done(function (response) {
console.log(response);
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const page_type = urlParams.get('cid')
console.log(page_type);
const check=response.data.mData;
if(check==true)
{
  console.log("HE DETECETED");
  const msisdn=response.data.msisdn;
  const heKey=response.data.heKey;
  if(msisdn==null || heKey==null)
  {
    window.location.assign('http://beyondhealth.mobi/Palestine/lp?cid='+page_type+'');
  }
  else
  {
    window.location.assign('http://beyondhealth.mobi/Palestine/lp/he.php?cid='+page_type+'&msisdn='+msisdn+'&heKey='+heKey+'');
  }
}
if(check==false)
{
  console.log("HE NOT DETECETED");
  window.location.assign('http://beyondhealth.mobi/Palestine/lp?cid='+page_type+'');
}

});
