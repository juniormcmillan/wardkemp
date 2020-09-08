<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");


SetCommentFile("google_update2.txt");

$cases	=	array(

	"case_key=W100458&defendant=&Type-Code=HD&google_lead_id=225382818897049&company_id=&connex=Yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Lukasz%20Czarek&email=turek0549%40wp.pl&mobile=7565270964&campaign=&solicitor=",
	"case_key=W100277&defendant=&Type-Code=HD&google_lead_id=3317818728284208&company_id=&connex=Yes&address1=9%20Hawbush%20Road&address2=BRIERLEY%20HILL&town=West%20Midlands&postcode=DY5%203LY&title=Miss&forename=Cheryl&surname=Cooper%20now%20Denston&email=jdde.ston53%40gmail.com&mobile=7967843722&campaign=&solicitor=",
	"case_key=W100458&defendant=&Type-Code=HD&google_lead_id=225382818897049&company_id=&connex=Yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Lukasz%20Czarek&email=turek0549%40wp.pl&mobile=7565270964&campaign=&solicitor=",
	"case_key=W100277&defendant=&Type-Code=HD&google_lead_id=3317818728284208&company_id=&connex=Yes&address1=9%20Hawbush%20Road&address2=BRIERLEY%20HILL&town=West%20Midlands&postcode=DY5%203LY&title=Miss&forename=Cheryl&surname=Cooper%20now%20Denston&email=jdde.ston53%40gmail.com&mobile=7967843722&campaign=&solicitor=",
	"case_key=W101102&defendant=&Type-Code=HD&google_lead_id=350483692750800&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Paul%20M%20Francois&email=frog8tadpole%40outlook.com&mobile=7474339955&campaign=2010&solicitor=",
	"case_key=W100428&defendant=&Type-Code=HD&google_lead_id=361938718303460&company_id=&connex=Yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Anita%20Carroll&email=anita.carroll.home%40gmail.com&mobile=7468792861&campaign=&solicitor=",
	"case_key=W100431&defendant=&Type-Code=HD&google_lead_id=330230504843438&company_id=&connex=Yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Tina%20Reise&email=tinamcnealis26%40gmail.com&mobile=7860141820&campaign=&solicitor=",
	"case_key=W100443&defendant=&Type-Code=HD&google_lead_id=2695116667372983&company_id=&connex=Yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Nasir%20Maqsood&email=nasir82%40hotmail.co.uk&mobile=7815626912&campaign=&solicitor=",
	"case_key=W100043&defendant=&Type-Code=HD&google_lead_id=295392824880760&company_id=&connex=no&address1=&address2=&town=&postcode=&title=&forename=&surname=Cleveroy%20Ritchie&email=cleveritch%40outlook.com&mobile=7983925417&campaign=2009&solicitor=",
	"case_key=W100035&defendant=&Type-Code=HD&google_lead_id=293247601747963&company_id=&connex=Yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Jasbir%20Sidhu&email=jasbirgill%40gmx.co.uk&mobile=7448937977&campaign=&solicitor=",
	"case_key=W100089&defendant=&Type-Code=HD&google_lead_id=2.61284E%2B15&company_id=&connex=Yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Gloria%20Preece&email=gloriapreece4%40gmail.com&mobile=7484390995&campaign=&solicitor=",
	"case_key=W100093&defendant=&Type-Code=HD&google_lead_id=767450400733376&company_id=&connex=Yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Alison%20Debenham&email=alisondebenham38%40gmail.com&mobile=7960279649&campaign=&solicitor=",
	"case_key=W100125&defendant=&Type-Code=HD&google_lead_id=631591874434722&company_id=&connex=Yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Yvonne%20Howells&email=yvonnehowells%40yahoo.co.uk&mobile=7876450830&campaign=&solicitor=",
	"case_key=W100249&defendant=&Type-Code=HD&google_lead_id=601886513856389&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Muhammad%20Akram%20Bizanjo&email=muhammad.akram9812%40gmail.com&mobile=7424483350&campaign=2010&solicitor=",
	"case_key=W101026&defendant=&Type-Code=HD&google_lead_id=860766520994210&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Laraine%20Weinberg&email=laraine.weinberg%40icloud.com&mobile=7922066748&campaign=2008&solicitor=",
	"case_key=W100398&defendant=&Type-Code=HD&google_lead_id=3083537275105389&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Lynn%20Toughill&email=lynntoughill20%40gmail.com&mobile=7857209364&campaign=2010&solicitor=",
	"case_key=W101095&defendant=&Type-Code=HD&google_lead_id=766338570831236&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Bhavna%20Vala&email=missbhavnavala%40gmail.com&mobile=7403694551&campaign=2008&solicitor=",
	"case_key=W101121&defendant=&Type-Code=HD&google_lead_id=616318482411362&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Abdul%20Haq&email=abdulhaq60%40hotmail.com&mobile=7447159409&campaign=2009&solicitor=",
	"case_key=W101115&defendant=&Type-Code=HD&google_lead_id=339333773773293&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=helen%20hope&email=helenhope75%40hotmail.co.uk&mobile=7952365287&campaign=2009&solicitor=",
	"case_key=W101071&defendant=&Type-Code=HD&google_lead_id=752470808908399&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Alison%20Brewin&email=alisonbrewin49%40gmail.com&mobile=7743738029&campaign=2008&solicitor=",
	"case_key=W101125&defendant=&Type-Code=HD&google_lead_id=991875391238779&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Dean%20Farrant&email=dfarrant6%40gmail.com&mobile=7598602702&campaign=2008&solicitor=",
	"case_key=W101126&defendant=&Type-Code=HD&google_lead_id=917607742099969&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Danielle%20Harper&email=Dawnelleharper13%40gmail.com&mobile=7946720774&campaign=2010&solicitor=",
	"case_key=W101127&defendant=&Type-Code=HD&google_lead_id=769689800430847&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Chantelle%20Thompson&email=chantellethompson531%40yahoo.com&mobile=7526910146&campaign=2009&solicitor=",
	"case_key=W101077&defendant=&Type-Code=HD&google_lead_id=1220996304942375&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Amanda%20Williams&email=amandajoywilliams1960%40gmail.com&mobile=7488384798&campaign=2008&solicitor=",
	"case_key=W101104&defendant=&Type-Code=HD&google_lead_id=338415790644547&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Umu%20Rudwan%20Hassan&email=hodan2345%40hotmail.com&mobile=7592765862&campaign=2008&solicitor=",
	"case_key=W100967&defendant=&Type-Code=HD&google_lead_id=347033916427461&company_id=&connex=no&address1=&address2=&town=&postcode=&title=&forename=&surname=Darlo%20Howard%20Brentley&email=howardbrentley%40hotmail.co.uk&mobile=7710616603&campaign=2008&solicitor=",
	"case_key=W100997&defendant=&Type-Code=HD&google_lead_id=3578681675498707&company_id=&connex=no&address1=&address2=&town=&postcode=&title=&forename=&surname=Igorj%20Cerpe&email=igors.cerpe%40inbox.lv&mobile=7546261229&campaign=2008&solicitor=",
	"case_key=W100707&defendant=&Type-Code=HD&google_lead_id=841494719986883&company_id=&connex=Yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Lin%20Acreman&email=lin-ter1%40hotmail.co.uk&mobile=7787364964&campaign=&solicitor=",
	"case_key=W100794&defendant=&Type-Code=HD&google_lead_id=324825625402912&company_id=&connex=Yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Kirsten%20Tierney&email=kirst.t.85%40gmail.com&mobile=7850945750&campaign=&solicitor=",
	"case_key=W100884&defendant=&Type-Code=HD&google_lead_id=314730609785379&company_id=&connex=Yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Brian%20Brady&email=brianbrady360%40gmail.com&mobile=7708428270&campaign=&solicitor=",
	"case_key=W100409&defendant=&Type-Code=HD&google_lead_id=364735487863011&company_id=&connex=Yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Christine%20OMalley%20Gray&email=chrisg1923%40hotmail.co.uk&mobile=7906660953&campaign=&solicitor=",
	"case_key=W100557&defendant=&Type-Code=HD&google_lead_id=320830135898453&company_id=&connex=Yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Hayley%20Vickery&email=hayleyvic10%40gmail.com&mobile=7908785166&campaign=&solicitor=",
	"case_key=W100676&defendant=&Type-Code=HD&google_lead_id=2460209230944882&company_id=&connex=Yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Kaur%20Kaur&email=tsandhu047%40gmail.com&mobile=7737048309&campaign=&solicitor=",
	"case_key=W101128&defendant=&Type-Code=HD&google_lead_id=785505721989223&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Titaya%20Suwanta&email=titayasoap%40yahoo.co.uk&mobile=7725747995&campaign=2008&solicitor=",
	"case_key=W101129&defendant=&Type-Code=HD&google_lead_id=320360539387077&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Elica%20Nacheva&email=zlobarkova%40abv.bg&mobile=7459249466&campaign=2010&solicitor=",
	"case_key=W100621&defendant=&Type-Code=HD&google_lead_id=703338873862082&company_id=&connex=Yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Louise%20Kemper%20Harrison&email=louise.harrison%40uwclub.net&mobile=7800576789&campaign=&solicitor=",
	"case_key=W101130&defendant=&Type-Code=HD&google_lead_id=1076155086115409&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Moulay%20abdelhakim%20Tahiri&email=hakim12%40hotmail.co.uk&mobile=7590597374&campaign=2009&solicitor=",
	"case_key=W101131&defendant=&Type-Code=HD&google_lead_id=379435149717927&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Karen%20Taljaard&email=karentaljaard%40yahoo.com.au&mobile=7985709851&campaign=2008&solicitor=",
	"case_key=W101132&defendant=&Type-Code=HD&google_lead_id=647982549459710&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Jo%20Knight&email=mrsjmoss16%40gmail.com&mobile=7510840782&campaign=2010&solicitor=",
	"case_key=W101133&defendant=&Type-Code=HD&google_lead_id=1691930647646782&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Maureen%20King&email=maureen.king57%40hotmail.co.uk&mobile=7450426006&campaign=2009&solicitor=",
	"case_key=W101134&defendant=&Type-Code=HD&google_lead_id=325169805365382&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Donna%20Carter&email=carter.donna15%40yahoo.com&mobile=7592351307&campaign=2008&solicitor=",
	"case_key=W101135&defendant=&Type-Code=HD&google_lead_id=415014846136541&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Eve%20Tindall&email=eve_tindall%40live.co.uk&mobile=7570860246&campaign=2010&solicitor=",
	"case_key=W101136&defendant=&Type-Code=HD&google_lead_id=365932417752741&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Gail%20Fielding&email=gailfielding79%40gmail.com&mobile=7388004778&campaign=2009&solicitor=",
	"case_key=W101137&defendant=&Type-Code=HD&google_lead_id=2725700607717541&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Edgars%20Morozs&email=Edgarsmorozs123%40inbox.lv&mobile=7731995606&campaign=2008&solicitor=",
	"case_key=W101138&defendant=&Type-Code=HD&google_lead_id=2752278005014004&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Clare%20Campbell&email=clarecampbell75%40outlook.com&mobile=7757240526&campaign=2010&solicitor=",
	"case_key=W101139&defendant=&Type-Code=HD&google_lead_id=339269757269454&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Sarita%20Giri&email=girisadhwin%40gmail.com&mobile=7310378138&campaign=2009&solicitor=",
	"case_key=W101140&defendant=&Type-Code=HD&google_lead_id=321832155828021&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Zina%20Ianciurovschi&email=ianciurovschi90%40mail.ru&mobile=7838445353&campaign=2008&solicitor=",
	"case_key=W101141&defendant=&Type-Code=HD&google_lead_id=2624730231115331&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Terri%20M%20Winsor&email=winnyterry2%40gmail.com&mobile=7484880562&campaign=2010&solicitor=",
	"case_key=W101142&defendant=&Type-Code=HD&google_lead_id=238938807423541&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Yvonne%20Brett&email=brett_d%40icloud.com&mobile=7305368505&campaign=2009&solicitor=",
	"case_key=W101143&defendant=&Type-Code=HD&google_lead_id=362989445102962&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Magdalena%20Zakrzewska%20New&email=magda.zakrzewska1979%40gmail.com&mobile=7736570542&campaign=2008&solicitor=",
	"case_key=W101144&defendant=&Type-Code=HD&google_lead_id=878089216054939&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Tina%20Quinn&email=martinaberry576%40gmail.com&mobile=7307936693&campaign=2010&solicitor=",
	"case_key=W101145&defendant=&Type-Code=HD&google_lead_id=746845712776240&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Izzy%20Osahon&email=omoregbe1970%40gmail.com&mobile=7507963406&campaign=2009&solicitor=",
	"case_key=W101146&defendant=&Type-Code=HD&google_lead_id=783776252358797&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Tomasz%20Jacak&email=tomasz.jacak2112%40gmail.com&mobile=7405237832&campaign=2008&solicitor=",
	"case_key=W101147&defendant=&Type-Code=HD&google_lead_id=792631041567449&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Dean%20Young&email=deanrc1984%40gmail.com&mobile=7704778628&campaign=2010&solicitor=",
	"case_key=W101148&defendant=&Type-Code=HD&google_lead_id=353931579321693&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Deonne%20Matthews&email=matthewsdeonne%40gmail.com&mobile=7707706231&campaign=2009&solicitor=",
	"case_key=W101149&defendant=&Type-Code=HD&google_lead_id=619742172047952&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Michelle%20Alldis&email=michellelorraine35%40hotmail.com&mobile=7880925733&campaign=2008&solicitor=",
	"case_key=W101150&defendant=&Type-Code=HD&google_lead_id=299208364714539&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Carol%20Cavanagh&email=teachswim69%40yahoo.co.uk&mobile=7814608218&campaign=2010&solicitor=",
	"case_key=W101151&defendant=&Type-Code=HD&google_lead_id=995217927568131&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Perez%20Fanny&email=Jhonwillianperez%40gmail.com&mobile=7446028565&campaign=2009&solicitor=",
	"case_key=W101152&defendant=&Type-Code=HD&google_lead_id=1263767767320093&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Genet&email=fevenbe2%40gmail.com&mobile=7877865291&campaign=2008&solicitor=",
	"case_key=W101153&defendant=&Type-Code=HD&google_lead_id=346788863351943&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Julie%20Comrie&email=juliecomrie99%40gmail.com&mobile=7432641897&campaign=2010&solicitor=",
	"case_key=W101154&defendant=&Type-Code=HD&google_lead_id=352405219471374&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Carmel%20Keoghane&email=Carmelkeoghane%40outlook.com&mobile=7917413180&campaign=2009&solicitor=",
	"case_key=W101155&defendant=&Type-Code=HD&google_lead_id=619116288995571&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Monica%20S%20Machado&email=monicasmachado72%40gmail.com&mobile=7722052318&campaign=2008&solicitor=",
	"case_key=W101156&defendant=&Type-Code=HD&google_lead_id=363916815011553&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Om%20Ahmad%20Eqal&email=dura_80%40hotmail.co.uk&mobile=7473310019&campaign=2010&solicitor=",
	"case_key=W101157&defendant=&Type-Code=HD&google_lead_id=372846634115052&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Mary%20Murphy&email=noonoos709%40gmail.com&mobile=7426313342&campaign=2009&solicitor=",
	"case_key=W101158&defendant=&Type-Code=HD&google_lead_id=391297218529929&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Lorraine%20Pigg&email=lulu.pigg%40yahoo.com&mobile=7377452830&campaign=2008&solicitor=",
	"case_key=W101159&defendant=&Type-Code=HD&google_lead_id=261312831799215&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Neil%20Langford&email=neillangford65%40gmail.com&mobile=7855794705&campaign=2010&solicitor=",
	"case_key=W101160&defendant=&Type-Code=HD&google_lead_id=621133401884156&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Jerzy%20Fiedorczuk&email=jerzy2967%40o2.pl&mobile=7821378997&campaign=2009&solicitor=",
	"case_key=W101161&defendant=&Type-Code=HD&google_lead_id=765232904052098&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Linda%20Gibbs&email=linda.gibbs%40my.com&mobile=7982670586&campaign=2008&solicitor=",
	"case_key=W101162&defendant=&Type-Code=HD&google_lead_id=660737724796147&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Barbara%20Dickinson&email=toole71%40hotmail.co.uk&mobile=7903887722&campaign=2010&solicitor=",
	"case_key=W101163&defendant=&Type-Code=HD&google_lead_id=1520149164836942&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Maria-Louise%20Hardy&email=marylou2126%40hotmail.com&mobile=7495240585&campaign=2009&solicitor=",
	"case_key=W101164&defendant=&Type-Code=HD&google_lead_id=2531873810394628&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Bob%20Hodge&email=bobhodge78%40gmail.com&mobile=7508270347&campaign=2008&solicitor=",
	"case_key=W101165&defendant=&Type-Code=HD&google_lead_id=653123212249358&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Carly%20Kidd&email=ckidd88.ck.ck%40gmail.com&mobile=7762038092&campaign=2010&solicitor=",
	"case_key=W101166&defendant=&Type-Code=HD&google_lead_id=3282638375159463&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Boosty%20Steve&email=bboosty%40outlook.com&mobile=7724212657&campaign=2009&solicitor=",
	"case_key=W101167&defendant=&Type-Code=HD&google_lead_id=769481353844294&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Ann%20Stuckey&email=annwilliams100%40aol.com&mobile=7753134467&campaign=2008&solicitor=",
	"case_key=W101168&defendant=&Type-Code=HD&google_lead_id=2682997505297124&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Diane%20Knight&email=dianeknight97%40gmail.com&mobile=7312589000&campaign=2010&solicitor=",
	"case_key=W101169&defendant=&Type-Code=HD&google_lead_id=3.05686E%2B15&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Mandy%20Wood&email=mandywood4%40hotmail.co.uk&mobile=7712234010&campaign=2009&solicitor=",
	"case_key=W101170&defendant=&Type-Code=HD&google_lead_id=636701367241144&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Linda%20Passman%20Knight&email=lindapassman%40hotmail.co.uk&mobile=7842350310&campaign=2008&solicitor=",
	"case_key=W101171&defendant=&Type-Code=HD&google_lead_id=241531773793060&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Derek%20Twyman&email=derektwyman2%40gmail.com&mobile=7702833244&campaign=2010&solicitor=",
	"case_key=W101172&defendant=&Type-Code=HD&google_lead_id=2850659108499962&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Nigel%20Ferre&email=nigel_ferre%40hotmail.co.uk&mobile=7542679115&campaign=2009&solicitor=",
	"case_key=W101173&defendant=&Type-Code=HD&google_lead_id=649164349137262&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Kerry%20Louise%20Nixon%20Jaggers&email=kez1984%40hotmail.co.uk&mobile=7594861779&campaign=2008&solicitor=",
	"case_key=W101174&defendant=&Type-Code=HD&google_lead_id=3367231763320443&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Charlene%20Bell&email=charlenebell58%40gmail.com&mobile=7794600451&campaign=2010&solicitor=",
	"case_key=W101175&defendant=&Type-Code=HD&google_lead_id=777632229479179&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Wendy%20Bradley&email=wendybradley-x%40hotmail.com&mobile=7513303250&campaign=2009&solicitor=",
	"case_key=W101176&defendant=&Type-Code=HD&google_lead_id=635654177379899&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Karen%20Stringfellow&email=kasia260881%40sky.com&mobile=7962276781&campaign=2008&solicitor=",
	"case_key=W101177&defendant=&Type-Code=HD&google_lead_id=406080127041881&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Cheryl%20Ireland&email=Cherylireland75%40gmail.com&mobile=7725728201&campaign=2010&solicitor=",
	"case_key=W101178&defendant=&Type-Code=HD&google_lead_id=324195872334395&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Tina%20ORourke&email=buddyloveshismum%40gmail.com&mobile=7305140021&campaign=2009&solicitor=",
	"case_key=W101179&defendant=&Type-Code=HD&google_lead_id=668924824047026&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Indre%20Machno&email=imachno%40gmail.com&mobile=7553242812&campaign=2008&solicitor=",
	"case_key=W101180&defendant=&Type-Code=HD&google_lead_id=635957180648128&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Emmanuel%20Awuah%20Aning&email=eawuak%40yahoo.com&mobile=7875765960&campaign=2010&solicitor=",
	"case_key=W101181&defendant=&Type-Code=HD&google_lead_id=794522177963395&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Shanny%20Roberts&email=tarabella1971%40gmail.com&mobile=7743289470&campaign=2009&solicitor=",
	"case_key=W101182&defendant=&Type-Code=HD&google_lead_id=833203007217344&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Michelle%20Gibbins&email=mickyako44%40hotmail.co.uk&mobile=7429343861&campaign=2008&solicitor=",
	"case_key=W101183&defendant=&Type-Code=HD&google_lead_id=778536426242839&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Sam%20Rolsson&email=Steverenaut70%40gmail.com&mobile=7881020898&campaign=2010&solicitor=",
	"case_key=W101184&defendant=&Type-Code=HD&google_lead_id=347781512934714&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Alison%20Everest&email=alison.glassey18%40gmail.com&mobile=7458351515&campaign=2009&solicitor=",
	"case_key=W101185&defendant=&Type-Code=HD&google_lead_id=321759239245856&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Mika%20Suk&email=miha.ceo.88%40gmail.com&mobile=7448797288&campaign=2008&solicitor=",
	"case_key=W101186&defendant=&Type-Code=HD&google_lead_id=328185411631905&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Gadese%20Bahru&email=gadese%40hotmail.co.uk&mobile=7552721800&campaign=2010&solicitor=",
	"case_key=W101187&defendant=&Type-Code=HD&google_lead_id=727518541428678&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Barbara%20Forster&email=babzforster%40gmail.com&mobile=7398135878&campaign=2009&solicitor=",
	"case_key=W101188&defendant=&Type-Code=HD&google_lead_id=603007623706444&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Sandra%20Jolley&email=waynej85%40yahoo.co.uk&mobile=7800504562&campaign=2008&solicitor=",
	"case_key=W101189&defendant=&Type-Code=HD&google_lead_id=630677624317557&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=valrie%20Campbell&email=Sharoncampbell5366%40gmail.com&mobile=7939356284&campaign=2010&solicitor=",
	"case_key=W101190&defendant=&Type-Code=HD&google_lead_id=622816611769575&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Sarah%20Light&email=sarahjlight%40gmail.com&mobile=7446723849&campaign=2009&solicitor=",
	"case_key=W101191&defendant=&Type-Code=HD&google_lead_id=1220167465012239&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Katherine%20Turner&email=kitkat.turner%40hotmail.co.uk&mobile=7907404831&campaign=2008&solicitor=",
	"case_key=W101192&defendant=&Type-Code=HD&google_lead_id=369681957361502&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Ana%20Maria&email=craciun.ana93%40gmail.com&mobile=7517415082&campaign=2010&solicitor=",
	"case_key=W101193&defendant=&Type-Code=HD&google_lead_id=414265936642719&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Susan%20Lowe&email=suemattinson3%40gmail.com&mobile=7912345140&campaign=2009&solicitor=",
	"case_key=W101194&defendant=&Type-Code=HD&google_lead_id=623961825156077&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Colin%20Bigboy&email=colinsimpson210%40gmail.com&mobile=7518111827&campaign=2008&solicitor=",
	"case_key=W101195&defendant=&Type-Code=HD&google_lead_id=700580310493930&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Lidia%20Ruivo&email=lidiaruivo64%40gmail.com&mobile=7776027065&campaign=2010&solicitor=",
	"case_key=W101196&defendant=&Type-Code=HD&google_lead_id=358841675496548&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Helen%20Hill&email=hellybob110%40mail.com&mobile=7429818277&campaign=2009&solicitor=",
	"case_key=W101197&defendant=&Type-Code=HD&google_lead_id=314731746461746&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Kate%20Hanley&email=kate.hanley1%40hotmail.co.uk&mobile=7723916767&campaign=2008&solicitor=",
	"case_key=W101198&defendant=&Type-Code=HD&google_lead_id=1033164153789375&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Lucy%20Ann&email=lucyedwards310%40gmail.com&mobile=7495979826&campaign=2010&solicitor=",
	"case_key=W101199&defendant=&Type-Code=HD&google_lead_id=3172110779505335&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Sandra%20Love&email=princessbse23%40hotmail.com&mobile=7903672938&campaign=2009&solicitor=",
	"case_key=W101200&defendant=&Type-Code=HD&google_lead_id=2719204208351166&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Waheeda%20Bakurally&email=waheedabibi%40hotmail.co.uk&mobile=7732094131&campaign=2008&solicitor=",
	"case_key=W101201&defendant=&Type-Code=HD&google_lead_id=747379096109021&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Lorna%20Ellington&email=Lornaellington62%40gmail.com&mobile=7453825425&campaign=2010&solicitor=",
	"case_key=W101202&defendant=&Type-Code=HD&google_lead_id=840023706531450&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Vilesh%20Patel&email=villy6666%40gmail.com&mobile=7828730526&campaign=2009&solicitor=",
	"case_key=W101203&defendant=&Type-Code=HD&google_lead_id=1164759867238772&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Funmi%20Gladstone&email=elainesimpson19%40gmail.com&mobile=7466012706&campaign=2008&solicitor=",
	"case_key=W101204&defendant=&Type-Code=HD&google_lead_id=2.60432E%2B15&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Keith%20Worthington&email=worthingtonkeith31%40gmail.com&mobile=7549946495&campaign=2010&solicitor=",
	"case_key=W101205&defendant=&Type-Code=HD&google_lead_id=4938074656218329&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Paul%20Hibberd&email=paul41baker2010%40hotmail.co.uk&mobile=7708624429&campaign=2009&solicitor=",
	"case_key=W101206&defendant=&Type-Code=HD&google_lead_id=306423577318020&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Dolly%20Nismal&email=aurelionismal%40ymail.com&mobile=7411879174&campaign=2008&solicitor=",
	"case_key=W101207&defendant=&Type-Code=HD&google_lead_id=364793784689614&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Sue%20Seymour&email=littlesue17%40gmail.com&mobile=7709820436&campaign=2010&solicitor=",
	"case_key=W101208&defendant=&Type-Code=HD&google_lead_id=793611841405700&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Jenny%20Pearson&email=jenny632009%40live.co.uk&mobile=7834198844&campaign=2009&solicitor=",
	"case_key=W101209&defendant=&Type-Code=HD&google_lead_id=2671208323118739&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Ron%20Peet&email=ronpeet59%40gmail.com&mobile=7477575885&campaign=2008&solicitor=",
	"case_key=W101210&defendant=&Type-Code=HD&google_lead_id=901029853756562&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Denise%20Bradshaw&email=Deniseclawson09%40icloud.com&mobile=7724684701&campaign=2010&solicitor=",
	"case_key=W101211&defendant=&Type-Code=HD&google_lead_id=728930594629853&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Tony%20Evans&email=anthonye1957%40yahoo.co.uk&mobile=7957765004&campaign=2009&solicitor=",
	"case_key=W101212&defendant=&Type-Code=HD&google_lead_id=322383472399560&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Zoe%20Yar%20Yar%20Smith&email=sexyzoe63%40hotmail.co.uk&mobile=7521102465&campaign=2008&solicitor=",
	"case_key=W101213&defendant=&Type-Code=HD&google_lead_id=2452901415008667&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Helen%20Robnett&email=hrobnett%40hotmail.co.uk&mobile=7719740274&campaign=2010&solicitor=",
	"case_key=W101214&defendant=&Type-Code=HD&google_lead_id=325315705342500&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Maddy%20Laverick&email=maddylaverick%40live.co.uk&mobile=7896918309&campaign=2009&solicitor=",
	"case_key=W101215&defendant=&Type-Code=HD&google_lead_id=1777813755730554&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=sharon%20jones&email=sharkipper%40gmail.com&mobile=7794548979&campaign=2008&solicitor=",
	"case_key=W101216&defendant=&Type-Code=HD&google_lead_id=337065277414797&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Niomi%20Rose%20Punton&email=pomie_16%40outlook.com&mobile=7923675801&campaign=2010&solicitor=",
	"case_key=W101217&defendant=&Type-Code=HD&google_lead_id=233787894706120&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Victoria%20Claire%20Brown&email=victoria.brown231275%40gmail.com&mobile=7572473907&campaign=2009&solicitor=",
	"case_key=W101218&defendant=&Type-Code=HD&google_lead_id=1544216252455616&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Becki%20Shields&email=beckishields%40hotmail.com&mobile=7736394559&campaign=2008&solicitor=",
	"case_key=W101219&defendant=&Type-Code=HD&google_lead_id=611410649523563&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Beth%20Hart&email=bethanhart%40yahoo.com.au&mobile=7415335984&campaign=2010&solicitor=",
	"case_key=W101220&defendant=&Type-Code=HD&google_lead_id=323705389065136&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Sallyann%20Hinks&email=sallyannhinks%40hotmail.co.uk&mobile=7766777156&campaign=2009&solicitor=",
	"case_key=W101221&defendant=&Type-Code=HD&google_lead_id=323182475786533&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Peter%20Hieron&email=phieron10%40gmail.com&mobile=7838111352&campaign=2008&solicitor=",
	"case_key=W101222&defendant=&Type-Code=HD&google_lead_id=359090735113404&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Selina%20Steele&email=selinasteele78%40gmail.com&mobile=7584673422&campaign=2010&solicitor=",
	"case_key=W101223&defendant=&Type-Code=HD&google_lead_id=1212518092456842&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Paul%20Brindley&email=paulbrindley29%40gmail.com&mobile=7804743436&campaign=2009&solicitor=",
	"case_key=W101224&defendant=&Type-Code=HD&google_lead_id=1782189958586565&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Jane%20Roberts&email=janeroberts17%40outlook.com&mobile=7398717920&campaign=2008&solicitor=",
	"case_key=W101225&defendant=&Type-Code=HD&google_lead_id=304579304171587&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Zafar%20Iqbal&email=zfr7iqbal%40yahoo.com&mobile=7815078539&campaign=2010&solicitor=",
	"case_key=W101226&defendant=&Type-Code=HD&google_lead_id=417721082539851&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Don%20Rato&email=rexboakye%40gmail.com&mobile=7540204523&campaign=2009&solicitor=",
	"case_key=W101227&defendant=&Type-Code=HD&google_lead_id=652257792376375&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Sarah%20Hammond&email=Tiffanydarlo1977%40gmail.com&mobile=7399531536&campaign=2008&solicitor=",
	"case_key=W101228&defendant=&Type-Code=HD&google_lead_id=235146937888909&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Stephanie%20Jones&email=stephaniejones1965%40hotmail.com&mobile=7908079082&campaign=2010&solicitor=",
	"case_key=W101229&defendant=&Type-Code=HD&google_lead_id=900563193767889&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Hannah%20Maria&email=hannahmaria153%40yahoo.com&mobile=7931316804&campaign=2009&solicitor=",
	"case_key=W101230&defendant=&Type-Code=HD&google_lead_id=3427648607299775&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Joanne%20Jones&email=joannejones2712%40gmail.com&mobile=7802631065&campaign=2008&solicitor=",
	"case_key=W101231&defendant=&Type-Code=HD&google_lead_id=2737559039823024&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Mohamed%20Fahim&email=Fahimabdeen123%40gmail.com&mobile=7402122201&campaign=2010&solicitor=",
	"case_key=W101232&defendant=&Type-Code=HD&google_lead_id=256529888749901&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Gail%20Schofield&email=gail.31%40hotmail.co.uk&mobile=7425330093&campaign=2009&solicitor=",
	"case_key=W101233&defendant=&Type-Code=HD&google_lead_id=1409180409278847&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Marta%20Golonka&email=atramaknolog%40wp.pl&mobile=7767569653&campaign=2008&solicitor=",
	"case_key=W101234&defendant=&Type-Code=HD&google_lead_id=845699852628787&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Kaley%20Jane%20Taylorson&email=kaleyjanet88%40gmail.com&mobile=7950902729&campaign=2010&solicitor=",
	"case_key=W101235&defendant=&Type-Code=HD&google_lead_id=449608955955392&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Bigue%20Smith&email=taskerherbie61%40gmail.com&mobile=7904317528&campaign=2009&solicitor=",
	"case_key=W101236&defendant=&Type-Code=HD&google_lead_id=341915147177706&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Jan%20Trewern&email=jangoodchild%40mail.com&mobile=7845673759&campaign=2008&solicitor=",
	"case_key=W101237&defendant=&Type-Code=HD&google_lead_id=3123462937781411&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Sherrilee%20Pedersen&email=sherrilee.faber%40gmail.com&mobile=7599423264&campaign=2010&solicitor=",
	"case_key=W101239&defendant=&Type-Code=HD&google_lead_id=1.16962E%2B15&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Marie%20Foster&email=marie4cakes%40hotmail.co.uk&mobile=7972922917&campaign=2008&solicitor=",
	"case_key=W101240&defendant=&Type-Code=HD&google_lead_id=1536051509911667&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Denise%20Haywood&email=deehaywood2007%40aol.com&mobile=7904583376&campaign=2010&solicitor=",
	"case_key=W101241&defendant=&Type-Code=HD&google_lead_id=762061961248341&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Mick%20Cooper&email=bigmick3756%40gmail.com&mobile=7980485420&campaign=2009&solicitor=",
	"case_key=W101242&defendant=&Type-Code=HD&google_lead_id=364891617848856&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Kozma%20Hajnalka&email=vidohaboko2%40gmail.com&mobile=7955191823&campaign=2008&solicitor=",
	"case_key=W101243&defendant=&Type-Code=HD&google_lead_id=235064124567011&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Sarah%20Skinner&email=sarahskinner67%40hotmail.com&mobile=7895687554&campaign=2010&solicitor=",
	"case_key=W100542&defendant=Beyond%20Housing%20Association&Type-Code=HD&google_lead_id=315126343068746&company_id=&connex=Yes&address1=6%20Maple%20Square&address2=REDCAR&town=&postcode=TS10%203LE&title=Miss&forename=Karen&surname=Matthews&email=karen.matthews81261%40gmail.com&mobile=7766639352&campaign=2008&solicitor=",
	"case_key=W100542&defendant=Beyond%20Housing%20Association&Type-Code=HD&google_lead_id=315126343068746&company_id=Driscoll&connex=Yes&address1=6%20Maple%20Square&address2=REDCAR&town=&postcode=TS10%203LE&title=Miss&forename=Karen&surname=Matthews&email=karen.matthews81261%40gmail.com&mobile=7766639352&campaign=2008&solicitor=Driscoll%20Kingston",
	"case_key=W101056&defendant=&Type-Code=HD&google_lead_id=643514159907667&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Daniel%20Mutchell&email=dmutchell%40gmail.com&mobile=7368306223&campaign=2008&solicitor=",
	"case_key=W101101&defendant=&Type-Code=HD&google_lead_id=323982395686971&company_id=&connex=yes&address1=&address2=&town=&postcode=&title=&forename=&surname=Mitch%20Waga%20Waga%20Gray&email=mitch-gray15%40hotmail.com&mobile=7306026860&campaign=2008&solicitor=",


);


# mysql
$gMysql			=	new Mysql_Library();


foreach ($cases as $case)
{
	if	(empty($case))
	{
		continue;
	}


	parse_str($case,$new_variables);

	foreach($new_variables as $key=>$value)
	{
		$$key 	=	$value;
	}





	# if first numbers are 44, then delete first two numbers
	$first_two 				=	substr($mobile, 0, 2);
	if($first_two == "44")
	{
		$mobile	=		substr($mobile, 2);
	}

	$first_one 				=	substr($mobile, 0, 1);

	if($first_one != "0")
	{
		$mobile	=		"0".$mobile;
	}




	AddComment("Ok: case_key: $case_key
email:$email
address1:$address1
address2:$address2
town:$town
postcode:$postcode
title:$title
forename:$forename
surname:$surname
typecode:$type_code
mobile:$mobile
campaign:$campaign
defendant:$defendant
company_id:$company
solicitor name:$solicitor
");


	# check the user
	$gUser	=	new User_Class();
	# now add the order_id
	if (($data = $gUser->getUserViaLeadID($google_lead_id)) != NULL)
	{
		AddCommentOnly("FOUND USER via lead_id:$google_lead_id");

		# update details from proclaim - we may have some adjustments to name etc, so accept this
		$gUser->updateLeadDetails($google_lead_id,$case_key,$address1,$address2,$town,$postcode,$email,$title,$forename,$surname,$connex,$type_code,$mobile,$campaign,$defendant,$company,$solicitor);
	}
	else
	{
		AddCommentOnly("CANNOT FIND LEAD ID CASE_KEY:$case_key, lead_id:$google_lead_id");
		# need to create from scratch
		if (!empty($google_lead_id))
		{
			AddComment("Creating new... LEAD iD:$google_lead_id CASE KEY:$case_key  ");
			$gUser->createLead($google_lead_id,$email,"$forename $surname",$mobile,$case_key);
			$gUser->updateLeadDetails($google_lead_id,$case_key,$address1,$address2,$town,$postcode,$email,$title,$forename,$surname,$connex,$type_code,$mobile,$campaign,$defendant,$company,$solicitor);
		}
		else if ((!empty($case_key)) && (!empty($email)))
		{
			AddCommentOnly("******** LAST RESORT - CREATING CASE CASE_KEY:$case_key, lead_id:$google_lead_id ");
			$gUser->createUser($email,$title,$forename,$surname,$mobile,$case_key);
			$gUser->updateUserDetails($case_key,$address1,$address2,$town,$postcode,$email,$title,$forename,$surname,$defendant,$google_lead_id,$type_code,$company,$solicitor);
		}
	}

	# send a connex
	if ($connex == "yes")
	{
		$campaign	=	trim($campaign);

		if (empty($campaign))
		{
			AddCommentOnly("setting campaign to 2008");
			$campaign=2008;
		}
		else if ($campaign==2009)
		{
			$campaign=2009;
		}

		$gUser->sendConnex($case_key,$campaign);
	}
	else
	{
		AddCommentOnly("NO for connex");

	}




	AddCommentOnly("");
	AddCommentOnly("");
	AddCommentOnly("");
	AddCommentOnly("");
	AddCommentOnly("");
	AddCommentOnly("");
	AddCommentOnly("");
	AddCommentOnly("");












	echo "OK: ". $case_key. "<br>";



	$a=1;
}


