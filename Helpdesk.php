<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rennaiscance IT</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <link rel="stylesheet" href="Style.css">
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</head>
<body class="helpdesk-page">
    <?php include 'navbar.php'; ?>
    <main class="container py-5">
      <h1 class="mb-4">Helpdesk artikelen</h1>

      <nav class="nav nav-pills justify-content-center mb-5">
        <a class="nav-link active" href="#" data-filter="all">Alle</a>
        <a class="nav-link" href="#" data-filter="email">E-mail</a>
        <a class="nav-link" href="#" data-filter="ftp">FTP</a>
      </nav>

            <div class="card article-card mb-4" data-category="email">
              <div class="d-flex align-items-center">
                <div class="me-3" style="flex: 0 0 180px;">
                  <img src="Media/Algemeen/thund.png" alt="" class="article-card-image img-fluid" />
                </div>
                <div class="flex-fill">
                  <div class="card-body p-0">
                    <h2 class="card-title">Uitgaande mailserver van bestaand e-mail account wijzigen in Mozilla Thunderbird</h2>
                    <a href="Helpdeskartikel.php?id=1" class="stretched-link"></a>
                  </div>
                </div>
              </div>
            </div>

            <div class="card article-card mb-4" data-category="email">
              <div class="d-flex align-items-center">
                <div class="me-3" style="flex: 0 0 180px;">
                  <img src="Media/Algemeen/mail.png" alt="" class="article-card-image img-fluid" />
                </div>
                <div class="flex-fill">
                  <div class="card-body p-0">
                    <h2 class="card-title">FTP verbinding instellen met Cyberduck</h2>
                    <a href="Helpdeskartikel.php?id=2" class="stretched-link"></a>
                  </div>
                </div>
              </div>
            </div>

            <div class="card article-card mb-4" data-category="email">
              <div class="d-flex align-items-center">
                <div class="me-3" style="flex: 0 0 180px;">
                  <img src="Media/Algemeen/mail2.png" alt="" class="article-card-image img-fluid" />
                </div>
                <div class="flex-fill">
                  <div class="card-body p-0">
                    <h2 class="card-title">E-mail adres instellen Mozilla Thunderbird</h2>
                    <p>Wilt u uw e-mailaccount instellen op uw iPhone of iPad? In dit artikel leggen we stap voor stap uit hoe u eenvoudig een e-mailaccount toevoegt in iOS. U leert hoe u uw accountinstellingen invoert, welke servergegevens nodig zijn en hoe u problemen oplost als de configuratie niet meteen lukt. Volg deze handleiding en binnen enkele minuten kunt u e-mails verzenden en ontvangen op uw iPhone!</p>
                    <a href="Helpdeskartikel.php?id=3" class="stretched-link"></a>
                  </div>
                </div>
              </div>
            </div>

            <div class="card article-card mb-4" data-category="email">
              <div class="d-flex align-items-center">
                <div class="me-3" style="flex: 0 0 180px;">
                  <img src="Media/Algemeen/webmail.png" alt="" class="article-card-image img-fluid" />
                </div>
                <div class="flex-fill">
                  <div class="card-body p-0">
                    <h2 class="card-title">Hoe activeer ik in Horde webmail de prullenmand functie?</h2>
                    <a href="Helpdeskartikel.php?id=4" class="stretched-link"></a>
                  </div>
                </div>
              </div>
            </div>

            <div class="card article-card mb-4" data-category="ftp">
              <div class="d-flex align-items-center">
                <div class="me-3" style="flex: 0 0 180px;">
                  <img src="Media/Algemeen/eend.png" alt="" class="article-card-image img-fluid" />
                </div>
                <div class="flex-fill">
                  <div class="card-body p-0">
                    <h2 class="card-title">FTP-verbinding instellen en opslaan in Cyberduck</h2>
                    <p>Cyberduck is een populaire en gebruiksvriendelijke FTP-client waarmee je eenvoudig verbinding kunt maken met een server en bestanden kunt beheren. In dit artikel leggen we uit hoe je Cyberduck downloadt, een FTP-verbinding instelt en opslaat voor toekomstig gebruik.</p>
                    <a href="Helpdeskartikel.php?id=5" class="stretched-link"></a>
                  </div>
                </div>
              </div>
            </div>

            <div class="card article-card mb-4" data-category="email">
              <div class="d-flex align-items-center">
                <div class="me-3" style="flex: 0 0 180px;">
                  <img src="Media/Algemeen/thund.png" alt="" class="article-card-image img-fluid" />
                </div>
                <div class="flex-fill">
                  <div class="card-body p-0">
                    <h2 class="card-title">E-mail adres instellen Mozilla Thunderbird</h2>
                    <a href="Helpdeskartikel.php?id=6" class="stretched-link"></a>
                  </div>
                </div>
              </div>
            </div>
    </main>

    <script>
      document.addEventListener('DOMContentLoaded', function(){
        const filters = document.querySelectorAll('.nav-link[data-filter]');
        const cards = document.querySelectorAll('.card.article-card');
        function applyFilter(name){
          cards.forEach(card => {
            const cat = card.dataset.category || 'email';
            if(name === 'all') card.classList.remove('d-none');
            else if(name === 'email') {
              if(cat === 'email') card.classList.remove('d-none'); else card.classList.add('d-none');
            } else if(name === 'ftp') {
              if(cat === 'ftp') card.classList.remove('d-none'); else card.classList.add('d-none');
            }
          });
        }
        filters.forEach(f => f.addEventListener('click', function(e){
          e.preventDefault();
          filters.forEach(x => x.classList.remove('active'));
          this.classList.add('active');
          applyFilter(this.dataset.filter);
        }));
      });
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>