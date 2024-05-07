// Activer le tooltip lors du survol des éléments dotés de l'attribut : data-toggle="tooltip"

$(document).ready(function(){
    // Cette fonction est exécutée lorsque le document HTML est entièrement chargé.

    // Sélectionne tous les éléments HTML ayant un attribut 'data-toggle' avec la valeur 'tooltip'
    $('[data-toggle="tooltip"]').tooltip();

    // Initialise le plugin de tooltip pour ces éléments
});
