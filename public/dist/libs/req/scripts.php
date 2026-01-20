<script src="../../dist/libs/jquery/dist/jquery.min.js"></script>
<script src="../../dist/libs/simplebar/dist/simplebar.min.js"></script>
<script src="../../dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<script src="../../dist/libs/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="../../dist/js/forms/sweet-alert.init.js"></script>
<!-- ---------------------------------------------- -->
<!-- core files -->
<!-- ---------------------------------------------- -->
<script src="../../dist/js/app.min.js"></script>
<script src="../../dist/js/app.init.js"></script>
<script src="../../dist/js/app-style-switcher.js"></script>
<script src="../../dist/js/sidebarmenu.js"></script>



<script src="../../dist/js/custom.js"></script>

<!-- ---------------------------------------------- -->
<!-- checkbox switch -->
<!-- ---------------------------------------------- -->
<script src="../../dist/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>
<script src="../../dist/js/forms/bootstrap-switch.js"></script>
<script src="../../dist/libs/prismjs/prism.js"></script>

<!-- ---------------------------------------------- -->
<!-- inputmask -->
<!-- ---------------------------------------------- -->
<script src="../../dist/libs/inputmask/dist/jquery.inputmask.min.js"></script>
<script src="../../dist/js/plugins/mask.init.js"></script>
<!-- ---------------------------------------------- -->
<!-- current page js files -->
<!-- ---------------------------------------------- -->
<script src="../../dist/js/apps/contact.js"></script>

<script src="../../dist/libs/dropzone/dist/min/dropzone.min.js"></script>

<script src="../../dist/libs/select2/dist/js/select2.full.min.js"></script>
<script src="../../dist/libs/select2/dist/js/select2.min.js"></script>
<script src="../../dist/js/forms/select2.init.js"></script>

<script>
  Dropzone.autoDiscover = false;

  const myDropzone = new Dropzone("#dropzone-fichierFATCA", {
    url: "/upload_fichier.php", // URL de traitement côté serveur
    paramName: "fileFATCA",     // Doit correspondre à ton champ
    maxFilesize: 5,             // Taille max en Mo
    maxFiles: 10,
    acceptedFiles: ".pdf,.jpg,.jpeg,.png",
    addRemoveLinks: true,
    dictDefaultMessage: "Glissez 'US IRS Form W-9' ici ou cliquez pour parcourir",

    // Empêche le formulaire global de se soumettre automatiquement
    autoProcessQueue: false
  });

  // Si tu veux uploader quand l'utilisateur clique sur "submit"
  document.querySelector("form").addEventListener("submit", function(e) {
    e.preventDefault(); // Empêche l'envoi direct du formulaire

    if (myDropzone.getQueuedFiles().length > 0) {
      myDropzone.processQueue(); // Lance l'upload Dropzone
    } else {
      this.submit(); // Aucun fichier à uploader via Dropzone, soumission normale
    }
  });

  // Quand tous les fichiers sont uploadés, on soumet le formulaire
  myDropzone.on("queuecomplete", function () {
    document.querySelector("form").submit();
  });
</script>
