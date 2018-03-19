<!DOCTYPE html>
<Html>
  <Head>
    <title>Embed Test</title>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" charset="utf-8"></script>
  </Head>
  <body>
  <div id="app">
    Checking Auth
  </div>
  <iframe id="login" style="display:none;width: 95vw; height: 95vh;">

  </iframe>
  <script type="text/javascript">
    var user = {!! json_encode($user) !!};
    var signinWin;
    var id;
    var cookies;
    $(document).ready(function() {
      if(user != null) {
        $("#app").html('Logged in externally');
      }else {
        var button = document.createElement("button");
        button.innerHTML = "Github Login"
        $(button).click(function(e) {
          e.preventDefault();
          signinWin = window.open("/auth/login/github?api=1", "SignIn", "width=780,height=410,toolbar=0,scrollbars=0,status=0,resizable=0,location=0,menuBar=0,left=" + 500 + ",top=" + 200);
          id = setInterval(checkLoginStatus, 500);
          signinWin.focus();
        });
        $("#app").html(button);
      }
    });
    function checkLoginStatus() {
      console.log("Check Login Status");
      if(!signinWin.closed && signinWin.location.href.search("/auth/success") != -1) {
        console.log(signinWin.document.cookie);
        cookies = (";" + signinWin.document.cookie).split(";auth_token=");
        signinWin.close();
        $("#app").html(cookies.pop().split(";").shift()); // set Request header with this
      }
      if(signinWin.closed) {
        window.clearInterval(id);
      }
    }
    function sleep(ms) {
      return new Promise(resolve => setTimeout(resolve, ms));
    }
  </script>
  </body>
</Html>
