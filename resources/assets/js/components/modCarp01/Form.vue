<template>
<div id="modCarpForm01">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title" data-widget="collapse">Informazioni Generali</h3>
            <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">

            <div class="form-group has-feedback " :class="{ 'has-error': form.errors.has('ragioneSociale') }">
              <label>Ragione Sociale</label>
              <input type="text" class="form-control" name="ragioneSociale" value="" v-model='form.ragioneSociale'>
            </div>

            <div class="form-group has-feedback " :class="{ 'has-error': form.errors.has('typeProd') }">
              <label>Tipologia di Produzione</label>
              {{-- <input type="text" class="form-control" name="descrizione" value="" placeholder="Descrizione System" v-model='form.descrizione'> --}}
              <input type="checkbox" id="checkbox" v-model="checked">
              {{-- <label for="checkbox">{{ checked }}</label> --}}
                {{-- <input type="checkbox" name="typeProdPorte" true-value="yes" false-value="no" v-model="form.typeProdPorte"> Porte --}}
                {{-- <input type="checkbox" id="typeProd_finestre" v-model='form.typeProd_finestre'> Finestre
                <input type="checkbox" id="typeProd_mobili" v-model='form.typeProd_mobili'> Mobili --}}
              <span class="help is-danger" v-if="form.errors.has('descrizione')" v-text="form.errors.get('descrizione')"></span>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    
}
</script>
