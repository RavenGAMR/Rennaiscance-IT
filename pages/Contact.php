<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rennaiscance IT</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <link rel="stylesheet" href="/stylesheet/Style.css">
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</head>
<body>
    <?php include __DIR__ . '/../components/navbar.php'; ?>

 

    <main class="container py-5">
    <h4>Heeft u een vraag over een product of dienst? Stel deze dan gerust. Bij ons kunt u gewoon op werkdagen tijdens kantooruren direct telefonisch terecht, of anders te allen tijde per email.</h4>

    <div class="row g-3 my-4">
      <div class="col-md-6">
        <div class="p-4 border rounded bg-light shadow-sm">
          <h5>Verkoop & administratie</h5>
          <p class="mb-0">info@renaissance.nl.</p>
          <p class="mb-0">06 - 515 33 214</p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="p-4 border rounded bg-light shadow-sm">
          <h5>Helpdesk & technische ondersteuning</h5>
          <p class="mb-0">helpdesk@renaissance.nl</p>
          <p class="mb-0">06 - 225 644 64</p>
        </div>
      </div>
    </div>

    <div class="row g-3 align-items-start my-4">
      <div class="col-lg-4">
        <div class="hoi p-4 border rounded bg-light shadow-sm">
          <h4>Bezoekadres</h4>
          <p class="mb-0">Renaissance IT</p>
          <p class="mb-0">Veraartlaan 4</p>
          <p class="mb-0">2288 GH Rijswijk ZH</p>

          <h4 class="mt-3">Postadres</h4>
          <p class="mb-0">Renaissance IT</p>
          <p class="mb-0">van Boeyenplantsoen 10</p>
          <p class="mb-0">2253WR Voorschoten</p>
          <p class="mb-0 mt-3">KvK: 28078857 te Den Haag</p>
          <p class="mb-0">BTW: NL807172856B01</p>
          <p class="mb-0">ABN AMRO: NL79ABNA0436946017</p>
          <p class="mb-0">Rabobank: NL16RABO0138429243</p>
          <p class="mb-0">ING Bank: NL09INGB0681019883</p>
        </div>
      </div>
      <div class="col-lg-8">
        <div id="googleMap" style="width:100%;height:400px;"></div>
      </div>
    </div>

    <script>
    function myMap() {
      var mapProp= {
        center:new google.maps.LatLng(52.0410868,4.3373716),
        zoom:17,
      };
      var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
    }
    </script>
    
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDUonX-LQIj3uCjgabva1OhtlVab1s9EHA&callback=myMap"></script>
    </main>
    <?php include __DIR__ . '/../components/footer.php'; ?>
</body>
</html>