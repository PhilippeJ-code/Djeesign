// Construction du menu
//
function initializeMenuHtml(parametres) {
  // Récupération de la liste des ids ( configuration ) et la liste des noms de design ( fonction ToHtml )
  //
  let listeIds = parametres.listeIds;
  let ids = listeIds.split(';');
  let listeIcones = parametres.listeIcones;
  let icones = listeIcones.split(';');
  let listeNoms = parametres.listeNoms;
  let noms = listeNoms.split(';');
  let isIconOnly = parametres.isIconOnly;
  let isMenuView = parametres.isMenuView;

  // On récupère l'élément Html d'id ul-container et on vide son contenu
  //
  let navMenu = $('#ul-container-' + parametres.uid);
  navMenu.empty();

  // On récupére l'id du design dans l'adresse de navigation
  //
  var $_GET = {};
  if (document.location.toString().indexOf('?') !== -1) {
    var query = document.location
      .toString()
      .replace(/^.*?\?/, '')
      .replace(/#.*$/, '')
      .split('&');

    for (var i = 0, l = query.length; i < l; i++) {
      var aux = decodeURIComponent(query[i]).split('=');
      $_GET[aux[0]] = aux[1];
    }
  }
  let planId = $_GET['plan_id'];
  if ( isMenuView == true) 
    planId = $_GET['view_id'];

  function updateQueryStringParameter(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
      return uri.replace(re, '$1' + key + "=" + value + '$2');
    }
    else {
      return uri + separator + key + "=" + value;
    }
  }

  function displayView(item) {

    let uri = document.location.toString();

    return updateQueryStringParameter(uri, 'view_id', item);

  }

  // Pour chacun des id, on ajoute le point de menu et on active le point de menu correspondant à l'adresse de navigation
  //   Avec un OnClick pour accéder au design correspondant à l'id planHeader_id
  //
  ids.forEach(ajouterMenu);
  function ajouterMenu(item, index) {
    let image = "";
    let imageSrc = "";
    if (index < icones.length) {
      image = icones[index];
      image = image.trim();
    }

    if (isMenuView == true) {

      let uri = displayView(item);
      let nImg = image.indexOf('.');

      if (isIconOnly == true) {
        if (image !== "") {
          if ( nImg != -1 )
            imageSrc = "<img src=\"data/img/" + image + "\" height=32px width=32px>";
          else
            imageSrc = "<i style='font-size:32px;' class='icon " + image + "'></i>";
        }

        if (item == planId)
          navMenu.append("<li class='active'><a href='" + uri + "'>" + imageSrc + "</a></li>");
        else
          navMenu.append("<li><a href='" + uri + "'>" + imageSrc + "</a></li>");
      }
      else {
        if (image !== "") {
          if ( nImg != -1 )
            imageSrc = "<img src=\"data/img/" + image + "\" height=16px width=16px>";
          else
            imageSrc = "<i style='font-size:16px;' class='icon " + image + "'></i>";
        }

        if (item == planId)
          navMenu.append("<li class='active'><a href='" + uri + "'>" + imageSrc + " " + noms[index] + "</a></li>");
        else
          navMenu.append("<li><a href='" + uri + "'>" + imageSrc + " " + noms[index] + "</a></li>");
      }

    } else {

      let nImg = image.indexOf('.');

      if (isIconOnly == true) {
        if (image !== "") {
          if ( nImg != -1 )
            imageSrc = "<img src=\"data/img/" + image + "\" height=32px width=32px>";
          else
            imageSrc = "<i style='font-size:32px;' class='icon " + image + "'></i>";
        }

        if (item == planId)
          navMenu.append("<li class='active'><a onClick='planHeader_id=" + item + "; displayPlan();'>" + imageSrc + "</a></li>");
        else
          navMenu.append("<li><a onClick='planHeader_id=" + item + "; displayPlan();'>" + imageSrc + "</a></li>");
      }
      else {
        if (image !== "") {
          if ( nImg != -1 )
            imageSrc = "<img src=\"data/img/" + image + "\" height=16px width=16px>";
          else
            imageSrc = "<i style='font-size:16px;' class='icon " + image + "'></i>";
        }

        if (item == planId)
          navMenu.append("<li class='active'><a onClick='planHeader_id=" + item + "; displayPlan();'>" + imageSrc + " " + noms[index] + "</a></li>");
        else
          navMenu.append("<li><a onClick='planHeader_id=" + item + "; displayPlan();'>" + imageSrc + " " + noms[index] + "</a></li>");
      }
    }
  }
}
