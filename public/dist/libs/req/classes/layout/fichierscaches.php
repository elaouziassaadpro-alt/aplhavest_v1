<?php
namespace Layout;

class fichierscaches {
    public function render() {
        ?>
        
        <input type="file" class="fichiers_caches" name="fichiers[ice]" id="ice">
        <input type="file" class="fichiers_caches" name="fichiers[status]" id="status">
        <input type="file" class="fichiers_caches" name="fichiers[rc]" id="rc">
        <input type="file" class="fichiers_caches" name="fichiers[agrement]" id="agrement">
        <input type="file" class="fichiers_caches" name="fichiers[ni_file]" id="ni_file">
        <input type="file" class="fichiers_caches" name="fichiers[fs_file]" id="fs_file">
        <input type="file" class="fichiers_caches" name="fichiers[rg_file]" id="rg_file">
        <input type="file" class="fichiers_caches" name="fichiers[loi_ep]" id="loi_ep">
        <input type="file" class="fichiers_caches" name="fichiers[etat_synthese]" id="etat_synthese">

        <input type="text" class="fichiers_caches" name="societe_de_gestion_check2" value="0">
        <input type="text" class="fichiers_caches" name="autorite_regulation_check2" value="0">
        <input type="text" class="fichiers_caches" name="activite_etranger_check2" value="0">
        <input type="text" class="fichiers_caches" name="sur_marche_financier_check2" value="0">
        <input type="text" class="fichiers_caches" name="us_entity_check2" value="0">
        <input type="text" class="fichiers_caches" name="giin2" value="0">
        <input type="text" class="fichiers_caches" name="mandataire2" value="0">
        <input type="text" class="fichiers_caches" name="dep_gestion2" value="0">


        <input type="file" class="fichiers_caches" name="fichiers[mandat_file]" id="mandat_file">

        <?php
    }
}

