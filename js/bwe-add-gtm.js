const bwe_gtm = BWEGTM.id;
const preComment = document.createComment(
    "Google Tag Manager (noscript)"
  );
const postComment = document.createComment(
    "End Google Tag Manager (noscript)"
  );
// append <noscript> to body
const noScript = document.createElement("noscript");
const iFrame = document.createElement("iframe");

//iFrame.src = 'https://www.googletagmanager.com/ns.html?id=GTM-XXXXXXGTM-XXXXXX';
iFrame.src = 'https://www.googletagmanager.com/ns.html?id=' +  bwe_gtm;
iFrame.height='0';
iFrame.width='0';
iFrame.style='display:none;visibility:hidden';

noScript.appendChild(iFrame);

const body = document.body.firstChild;
body.parentNode.insertBefore(preComment, body)
body.parentNode.insertBefore(noScript, body);
body.parentNode.insertBefore(postComment, body);