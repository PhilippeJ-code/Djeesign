  // Construction du menu
  //
  function initializeMenuHtml(parametres)
  {
    // Récupération de la liste des ids ( configuration ) et la liste des noms de design ( fonction ToHtml )
    //
    let listeIds = parametres.listeIds;
    let ids = listeIds.split(';');
    let listeIcones = parametres.listeIcones;
    let icones = listeIcones.split(';');
    let listeNoms = parametres.listeNoms;
    let noms = listeNoms.split(';');

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

    // Pour chacun des id, on ajoute le point de menu et on active le point de menu correspondant à l'adresse de navigation
    //   Avec un OnClick pour accéder au design correspondant à l'id planHeader_id
    //
    ids.forEach(ajouterMenu);
    function ajouterMenu(item, index) {
      let image = "";
      let imageSrc = "";
      if (index < icones.length )
      {
        image = icones[index];
        image = image.trim();
      }
      if (image !== "")
      {
        imageSrc ="<img src=\"data/img/" + image + "\" height=16px width=16px>"
      }

      if (item == planId)
        navMenu.append("<li class='active'><a onClick='planHeader_id=" + item + "; displayPlan();'>" + imageSrc + noms[index] + "</a></li>");
      else
        navMenu.append("<li><a onClick='planHeader_id=" + item + "; displayPlan();'>" + imageSrc + noms[index] + "</a></li>");
    }

  }
