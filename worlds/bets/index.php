<html>
<head>
  <meta charset="utf-8" />
  <title>Lol Codex - Worlds Data</title>
  <link rel="shortcut icon" type="image/png" href="https://LoLCodex.com/pictures/logos/TribalPenguin.ico?3">
  <meta property="og:title" content="Submit Your Bets | LoLCodex.com" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="http://LoLCodex.com/worlds/bets/" />
  <meta property="og:image" content="http://LoLCodex.com/worlds/ogImage.png" />
  <meta property="og:description" content="Submit your bets before League of Legends Worlds 2024 begins on September 25th!" />

  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
  <link rel="stylesheet" href="../../base.css?<?php echo time() ?>">
  <link rel="stylesheet" href="worlds-bets.css?<?php echo time() ?>">
</head>
<body>
  <?php
    include "../../parts/header.php";
  ?>

  <div id="content">
    <h1 style="margin-bottom: 0px;">Worlds Bets</h1>
    <div id="countdown"></div>
    <div id="worlds-bets-container">
      <p style="margin-top: 0px; margin-bottom: 0px;">Submit your guesses before Worlds begin on <span class="important">September 25th!</span></p>
      <div id="worlds-bets-steps-hidden" style="visibility: invisible"></div>
      <div id="worlds-bets-steps">
        <div id="bets-step-1" class="bets-step-container">
          <p>What would you like to do?</p>
          <p class="sub-text">If you submitted bets last year, please hit the <span class="important">"Submit Bets!"</span> button again to add your bets for this year!</p>
          <a href="javascript:nextStep(2);"><div id="bet-button" class="worlds-button active">Submit bets!</div></a>
          <a href="javascript:nextStep(3);"><div id="bet-button" class="worlds-button active">Update bets!</div></a>
        </div>
        <div id="bets-step-2" style="display: none;" class="bets-step-container">
          <p>Please enter the <span class="important">name</span> you would like your bets to display with, and a <span class="important">password</span> so you can update them later:</p>
          <p id="bets-create-user-pass-error" class="error hidden">Please enter a valid username and password.</p>
          <p id="bets-user-pass-exists-error" class="error hidden">A record already exists for this username. Please choose a different one or hit Back and choose "Update Bets".</p>
          <label for="bets-displayname-create">Display Name</label><br/><input type="text" id="bets-displayname-create" value="" name="Bets Display Name"/>
          <br/><br/>
          <label for="bets-password-create">Password (Optional)</label><br/><input type="password" id="bets-password-create" value="" name="Bets Password"/>
          <br/><br/>
          <a href="javascript:lastStep(1);"><div id="bet-button" class="worlds-button active">Back</div></a>
          <a href="javascript:checkLogin();"><div id="bet-button" class="worlds-button active">Next</div></a>
        </div>
        <div id="bets-step-3" style="display: none;" class="bets-step-container">
          <p>Please enter your <span class="important">name</span> and the <span class="important">password</span> you used when you first entered your bets:</p>
          <p id="bets-update-user-pass-error" class="error hidden">The records indicate you haven't submitted any new bets for this year. Please hit 'Back' and then the 'Submit Bets' button.</p>
          <label for="bets-displayname-update">Display Name:</label><br/><input type="text" id="bets-displayname-update" value="" name="Bets Display Name"/>
          <br/><br/>
          <label for="bets-password-update">Password (Optional)</label><br/><input type="password" id="bets-password-update" value="" name="Bets Password"/>
          <p class="sub-text">If you didn't previously set password, you can add one by typing it in now and saving your updated bets</p>
          <br/><br/>
          <a href="javascript:lastStep(1);"><div id="bet-button" class="worlds-button active">Back</div></a>
          <a href="javascript:getBets();"><div id="bet-button" class="worlds-button active">Next</div></a>
        </div>
        <div id="bets-step-4" style="display: none;" class="bets-step-container">
          <h2>Submit your bets</h2>
          <p id="bets-data-error" class="error hidden">There was a problem adding or updating your best. Please reach out to Dragon Ferocity on Discord!</p>
          <p id="bets-data-error-unfilled" class="error hidden">Please add an answer for every bet!</p>
          <p id="bets-data-error-duplicate" class="error hidden">Please make sure each answer is unique!</p>
          <p id="bets-data-error-unknown" class="error hidden">An unspecified error has occurred: </p>
          <div class="bet-container">
            <h3>Bet #1</h3>
            <p class="header">What <span class="important">3 champions</span> do you think will have the highest <span class="important">presence</span> this year?</p>
            <p class="sub-text">To calculate a <span class="important">champions presence</span>, add the number of games they are <span class="important">picked</span> with the number of games they are <span class="important">banned</span> and compare that to the total number of games played in the worlds tournament, 109 games last year</p>
            <p class="sub-text">Please pick three different champions. The total presence will be calculated separately for each champion picked and the three totals added together (IE, a single game can be counted three times if all three of your choices are picked or banned in that game).</p>
            Champion #1 <div id="bet-1-a" class="select champion-select"></div>
            Champion #2 <div id="bet-1-b" class="select champion-select"></div>
            Champion #3 <div id="bet-1-c" class="select champion-select"></div>
          </div>
          <div class="bet-container">
            <h3>Bet #2</h3>
            <p class="header">What 3 types of <span class="important">elemental dragons</span> will be <span class="important">slain</span> the most this year?</p>
            <p class="sub-text">Does not include <span class="important">Elder Dragon</span>. Please pick different dragon types!</p>
            <br/>
            <div id="bet-2-a" class="select dragon-types"></div>
            <div id="bet-2-b" class="select dragon-types"></div>
            <div id="bet-2-c" class="select dragon-types"></div>
          </div>
          <div class="bet-container">
            <h3>Bet #3</h3>
            <p class="header">What do you think will be the <span class="important">win differential</span> (total wins minus total losses) of the three <span class="important">dragon champions</span> at Worlds this year?</p>
            <p class="sub-text">The three dragon champions are: Aurelion Sol, Smolder, and Shyvana</p>
            <br/>
            <div class="range-container">
              <input type="range" id="bet-3" class="slider" min="-122" max="122" step="1" name="Games won by dragon champions"/>
              <p class="slider-value"></p>
            </div>
          </div>
          <a href="javascript:lastStep(1);"><div id="bet-button" class="worlds-button active">Back</div></a>
          <a href="javascript:updateBets();"><div id="bet-button" class="worlds-button active">Submit</div></a>
        </div>
        <div id="bets-step-5" style="display: none;" class="bets-step-container">
          <p>Your bets have been recorded!</p>
          <a href="javascript:lastStep(1);"><div id="bet-button" class="worlds-button active">Back</div></a>
        </div>
      </div>
    </div>
    <div id="worlds-bets-not-open" style="display: none;">
      <p>You may submit your bets beginning on October 4th, 2023. Bets close on October 8th at 11pm CST.<br/> See you then!</p>
      <a href="../"><div id="bet-button" class="worlds-button active">Back to Worlds Graphs</div></a>
    </div>
    <div id="worlds-bets-closed" style="display: none;">
      <p>Bets have closed for 2023</p>
      <a href="../"><div id="bet-button" class="worlds-button active">Back to Worlds Graphs</div></a>
    </div>
    <br/><br/>

    <div style="height: 5em;"></div>
  </div>
  <div id="loading" style="display: none; position: fixed; width: 100%; height: 100%; left: 0px; top: 0px; background-color: rgba(0, 0, 0, 0.5); color: white; text-align: center;">
    <div style="position: relative; left: 50%; top: 50%; transform: translate(-50%, -50%);">
      <div class="lds-ripple"><div></div><div></div></div>
    </div>
  </div>
  <?php
    include "../../parts/footer.php";
  ?>

  <template id="champions-select">
    <select>
      <option value="Aatrox">Aatrox</option>
      <option value="Ahri">Ahri</option>
      <option value="Akali">Akali</option>
      <option value="Akshan">Akshan</option>
      <option value="Alistar">Alistar</option>
      <option value="Amumu">Amumu</option>
      <option value="Anivia">Anivia</option>
      <option value="Annie">Annie</option>
      <option value="Aphelios">Aphelios</option>
      <option value="Ashe">Ashe</option>
      <option value="Aurelion Sol">Aurelion Sol</option>
      <option value="Aurora">Aurora</option>
      <option value="Azir">Azir</option>
      <option value="Bard">Bard</option>
      <option value="Bel'Veth">Bel'Veth</option>
      <option value="Blitzcrank">Blitzcrank</option>
      <option value="Brand">Brand</option>
      <option value="Braum">Braum</option>
      <option value="Briar">Briar</option>
      <option value="Caitlyn">Caitlyn</option>
      <option value="Camille">Camille</option>
      <option value="Cassiopeia">Cassiopeia</option>
      <option value="Cho'Gath">Cho'Gath</option>
      <option value="Corki">Corki</option>
      <option value="Darius">Darius</option>
      <option value="Diana">Diana</option>
      <option value="Dr. Mundo">Dr. Mundo</option>
      <option value="Draven">Draven</option>
      <option value="Ekko">Ekko</option>
      <option value="Elise">Elise</option>
      <option value="Evelynn">Evelynn</option>
      <option value="Ezreal">Ezreal</option>
      <option value="Fiddlesticks">Fiddlesticks</option>
      <option value="Fiora">Fiora</option>
      <option value="Fizz">Fizz</option>
      <option value="Galio">Galio</option>
      <option value="Gangplank">Gangplank</option>
      <option value="Garen">Garen</option>
      <option value="Gnar">Gnar</option>
      <option value="Gragas">Gragas</option>
      <option value="Graves">Graves</option>
      <option value="Gwen">Gwen</option>
      <option value="Hecarim">Hecarim</option>
      <option value="Heimerdinger">Heimerdinger</option>
      <option value="Hwei">Hwei</option>
      <option value="Illaoi">Illaoi</option>
      <option value="Irelia">Irelia</option>
      <option value="Ivern">Ivern</option>
      <option value="Janna">Janna</option>
      <option value="Jarvan IV">Jarvan IV</option>
      <option value="Jax">Jax</option>
      <option value="Jayce">Jayce</option>
      <option value="Jhin">Jhin</option>
      <option value="Jinx">Jinx</option>
      <option value="K'Sante">K'Sante</option>
      <option value="Kai'Sa">Kai'Sa</option>
      <option value="Kalista">Kalista</option>
      <option value="Karma">Karma</option>
      <option value="Karthus">Karthus</option>
      <option value="Kassadin">Kassadin</option>
      <option value="Katarina">Katarina</option>
      <option value="Kayle">Kayle</option>
      <option value="Kayn">Kayn</option>
      <option value="Kennen">Kennen</option>
      <option value="Kha'Zix">Kha'Zix</option>
      <option value="Kindred">Kindred</option>
      <option value="Kled">Kled</option>
      <option value="Kog'Maw">Kog'Maw</option>
      <option value="LeBlanc">LeBlanc</option>
      <option value="Lee Sin">Lee Sin</option>
      <option value="Leona">Leona</option>
      <option value="Lillia">Lillia</option>
      <option value="Lissandra">Lissandra</option>
      <option value="Lucian">Lucian</option>
      <option value="Lulu">Lulu</option>
      <option value="Lux">Lux</option>
      <option value="Malphite">Malphite</option>
      <option value="Malzahar">Malzahar</option>
      <option value="Maokai">Maokai</option>
      <option value="Master Yi">Master Yi</option>
      <option value="Milio">Milio</option>
      <option value="Miss Fortune">Miss Fortune</option>
      <option value="Mordekaiser">Mordekaiser</option>
      <option value="Morgana">Morgana</option>
      <option value="Naafiri">Naafiri</option>
      <option value="Nami">Nami</option>
      <option value="Nasus">Nasus</option>
      <option value="Nautilus">Nautilus</option>
      <option value="Neeko">Neeko</option>
      <option value="Nidalee">Nidalee</option>
      <option value="Nilah">Nilah</option>
      <option value="Nocturne">Nocturne</option>
      <option value="Nunu & Willump">Nunu & Willump</option>
      <option value="Olaf">Olaf</option>
      <option value="Orianna">Orianna</option>
      <option value="Ornn">Ornn</option>
      <option value="Pantheon">Pantheon</option>
      <option value="Poppy">Poppy</option>
      <option value="Pyke">Pyke</option>
      <option value="Qiyana">Qiyana</option>
      <option value="Quinn">Quinn</option>
      <option value="Rakan">Rakan</option>
      <option value="Rammus">Rammus</option>
      <option value="Rek'Sai">Rek'Sai</option>
      <option value="Rell">Rell</option>
      <option value="Renata Glasc">Renata Glasc</option>
      <option value="Renekton">Renekton</option>
      <option value="Rengar">Rengar</option>
      <option value="Riven">Riven</option>
      <option value="Rumble">Rumble</option>
      <option value="Ryze">Ryze</option>
      <option value="Samira">Samira</option>
      <option value="Sejuani">Sejuani</option>
      <option value="Senna">Senna</option>
      <option value="Seraphine">Seraphine</option>
      <option value="Sett">Sett</option>
      <option value="Shaco">Shaco</option>
      <option value="Shen">Shen</option>
      <option value="Shyvana">Shyvana</option>
      <option value="Singed">Singed</option>
      <option value="Sion">Sion</option>
      <option value="Sivir">Sivir</option>
      <option value="Skarner">Skarner</option>
      <option value="Smolder">Smolder</option>
      <option value="Sona">Sona</option>
      <option value="Soraka">Soraka</option>
      <option value="Swain">Swain</option>
      <option value="Sylas">Sylas</option>
      <option value="Syndra">Syndra</option>
      <option value="Tahm Kench">Tahm Kench</option>
      <option value="Taliyah">Taliyah</option>
      <option value="Talon">Talon</option>
      <option value="Taric">Taric</option>
      <option value="Teemo">Teemo</option>
      <option value="Thresh">Thresh</option>
      <option value="Tristana">Tristana</option>
      <option value="Trundle">Trundle</option>
      <option value="Tryndamere">Tryndamere</option>
      <option value="Twisted Fate">Twisted Fate</option>
      <option value="Twitch">Twitch</option>
      <option value="Udyr">Udyr</option>
      <option value="Urgot">Urgot</option>
      <option value="Varus">Varus</option>
      <option value="Vayne">Vayne</option>
      <option value="Veigar">Veigar</option>
      <option value="Vel'Koz">Vel'Koz</option>
      <option value="Vex">Vex</option>
      <option value="Vi">Vi</option>
      <option value="Viego">Viego</option>
      <option value="Viktor">Viktor</option>
      <option value="Vladimir">Vladimir</option>
      <option value="Volibear">Volibear</option>
      <option value="Warwick">Warwick</option>
      <option value="Wukong">Wukong</option>
      <option value="Xayah">Xayah</option>
      <option value="Xerath">Xerath</option>
      <option value="Xin Zhao">Xin Zhao</option>
      <option value="Yasuo">Yasuo</option>
      <option value="Yone">Yone</option>
      <option value="Yorick">Yorick</option>
      <option value="Yuumi">Yuumi</option>
      <option value="Zac">Zac</option>
      <option value="Zed">Zed</option>
      <option value="Zeri">Zeri</option>
      <option value="Ziggs">Ziggs</option>
      <option value="Zilean">Zilean</option>
      <option value="Zoe">Zoe</option>
      <option value="Zyra">Zyra</option>
    </select>
  </template>
  <template id="dragon-types">
    <select>
      <option value="Chemtech">Chemtech Drake</option>
      <option value="Cloud">Cloud Drake</option>
      <option value="Hextech">Hextech Drake</option>
      <option value="Infernal">Infernal Drake</option>
      <option value="Mountain">Mountain Drake</option>
      <option value="Ocean">Ocean Drake</option>
    </select>
  </template>

  <script src="./bets.js?3"></script>
</body>

</html>

<!--
Max # of Games

Play Ins: 36
Swiss: 51
Knockouts: 35

-->