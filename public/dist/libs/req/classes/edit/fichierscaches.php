<?php
namespace Edit;

class fichierscaches {
    public function render() {
        ?>
        
        <input type="file" class="fichiers_caches" name="fichiers[ice]" id="ice">
        <input type="file" class="fichiers_caches" name="fichiers[status]" id="status">
        <input type="file" class="fichiers_caches" name="fichiers[rc]" id="rc">
        <input type="file" class="fichiers_caches" name="fichiers[agrement]" id="agrement">
        <input type="file" class="fichiers_caches" name="fichiers[ni_fs_rg]" id="ni_fs_rg">
        <input type="file" class="fichiers_caches" name="fichiers[loi_ep]" id="loi_ep">
        <input type="file" class="fichiers_caches" name="fichiers[etat_synthese]" id="etat_synthese">


        <input type="file" class="fichiers_caches" name="mandat_pouvoir" id="mandat_pouvoir">

        <?php
    }
}