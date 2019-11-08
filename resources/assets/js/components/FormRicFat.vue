<template>
  <form method="POST" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)" @change='checkGoOn'>
    <!-- DATI ANAGRAFICI -->
    <boxDefault header=true>
      <template v-slot:header>
        {{trans('client.dataCli')}}
      </template>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('codicecf') }">
        <label>{{trans('client.codeCli')}}</label>
        <input
          type="text"
          class="form-control"
          name="codicecf"
          v-model="form.codicecf"
          disabled
        >
        <transition name="fade">
              <span
              class="help-block"
              v-if="form.errors.has('codicecf')"
              v-text="form.errors.get('codicecf')"
              ></span>
          </transition>
      </div>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('ragione_sociale') }" >
        <label>{{trans('client.descCli')}}</label>
        <input
          type="text"
          class="form-control"
          name="ragione_sociale"
          v-model="form.ragione_sociale"
        >
        <transition name="fade">
              <span
              class="help-block"
              v-if="form.errors.has('ragione_sociale')"
              v-text="form.errors.get('ragione_sociale')"
              ></span>
          </transition>
      </div>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('richiedente') }" >
        <label>{{trans('modRicFat.richiedente')}} ({{trans('modRicFat.nomeCognome')}})</label>
        <input
          type="text"
          class="form-control"
          name="richiedente"
          v-model="form.richiedente"
        >
        <transition name="fade">
              <span
              class="help-block"
              v-if="form.errors.has('richiedente')"
              v-text="form.errors.get('richiedente')"
              ></span>
          </transition>
      </div>
      
      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('email_richiedente') }" >
        <label>Email</label>
        <input
          type="email"
          class="form-control"
          name="email_richiedente"
          v-model="form.email_richiedente"
        >
        <transition name="fade">
              <span
              class="help-block"
              v-if="form.errors.has('email_richiedente')"
              v-text="form.errors.get('email_richiedente')"
              ></span>
          </transition>
      </div>
        
    </boxDefault>
    
    <!-- DESCRIZIONE PERSONALIZZAZIONE -->
    <boxDefault header=true v-show="isStep1End && isStep1End!=null">
      <template v-slot:header>
        {{trans('modRicFat.dettagliPers')}}
      </template>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('tipologia_prodotto') }">
        <label>{{trans('modRicFat.tipologiaProd')}}?</label>
        <v-multi-select
          v-model="form.tipologia_prodotto"
          :options="optionsSysOther"
          :multiple="false"
          :searchable="true"
          placeholder="Pick a value"
          label="descrizione"
          track-by="codice"
          @close="personalEventSelect('tipologia_prodotto')"
        ></v-multi-select>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('tipologia_prodotto')"
            v-text="form.errors.get('tipologia_prodotto')"
          ></span>
        </transition>
      </div>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('descr_pers') }" v-show="form.tipologia_prodotto && form.tipologia_prodotto!=null">
        <label>{{trans('modRicFat.descrPers')}}</label>
        <textarea
          class="form-control"
          name='descr_pers'
          rows="5"
          v-model="form.descr_pers"
          :placeholder="trans('modRicFat.insertNote')"
        ></textarea>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('descr_pers')"
            v-text="form.errors.get('descr_pers')"
          ></span>
        </transition>
      </div>

    </boxDefault>

    <!-- PRODOTTO DI RIFERIMENTO -->
    <boxDefault header=true v-show="isStep2End && isStep2End!=null">
      <template v-slot:header>
        {{trans('modRicFat.prodRiferimento')}}
      </template>

      <div class="form-group has-feedback">
        <label>{{trans('modRicFat.prodRif_ask')}}</label>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="isRifProdKK"
          color="info"
          v-model="isRifProdKK"
          value=1
        >
          <i class="icon fa fa-check" slot="extra"></i>
          {{trans('modRicFat.prodRif_answerKK')}}
        </pRadio>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="isRifProdKK"
          color="info"
          v-model="isRifProdKK"
          value=2
        >
          <i class="icon fa fa-check" slot="extra"></i>
          {{trans('modRicFat.prodRif_answerOther')}}
        </pRadio>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="isRifProdKK"
          color="info"
          v-model="isRifProdKK"
          value=3
        >
          <i class="icon fa fa-check" slot="extra"></i>
          No
        </pRadio>
      </div>
      
      <hr>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('system_kk') }" v-show="isRifProdKK==1">
        <label>Selezionare System Krona Koblenz</label>
        <v-multi-select
          v-model="form.system_kk"
          :options="optionsSysMkt"
          :multiple="false"
          :searchable="true"
          placeholder="Pick a value"
          label="descrizione"
          track-by="codice"
          @close="personalEventSelect('system_kk')"
        ></v-multi-select>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('system_kk')"
            v-text="form.errors.get('system_kk')"
          ></span>
        </transition>
      </div>
      
      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('system_other') }"  v-show="isRifProdKK==2">
        <label>Inserire Breve Riferimento Prodotto</label>
        <input
          type="text"
          class="form-control"
          name="system_other"
          v-model="form.system_other"
        >
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('system_other')"
            v-text="form.errors.get('system_other')"
          ></span>
        </transition>
      </div>

    </boxDefault>

    <!-- INFO TECNICHE E COMMERCIALI -->
    <boxDefault header=true v-show="isStep3End && isStep3End!=null">
      <template v-slot:header>
        {{trans('modRicFat.info_tech_comm')}}
      </template>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('info_tecn_comm') }">
        <label>{{trans('modRicFat.ins_info_tech_comm')}}</label>
        <textarea
          class="form-control"
          name='descr_pers'
          rows="5"
          v-model="form.info_tecn_comm"
          :placeholder="trans('modRicFat.insertNote')"
        ></textarea>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('info_tecn_comm')"
            v-text="form.errors.get('info_tecn_comm')"
          ></span>
        </transition>
      </div>

    </boxDefault>

    <!-- INFO DI PRODUZIONE -->
    <boxDefault header=true v-show="isStep4End && isStep4End!=null">
      <template v-slot:header>
        {{trans('modRicFat.info_prod')}}
      </template>
      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('um') }">
        <label>{{trans('modRicFat.um')}}</label>
        <v-multi-select
          v-model="form.um"
          :options="optionsUM"
          :multiple="false"
          :searchable="true"
          placeholder="Pick a value"
          label="descrizione"
          track-by="codice"
        ></v-multi-select>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('um')"
            v-text="form.errors.get('um')"
          ></span>
        </transition>
      </div>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('quantity') }">
        <label>{{trans('modRicFat.qta')}}</label>
          <input
            type="number"
            class="form-control"
            name="quantity"
            v-model="form.quantity"
          >
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('quantity')"
            v-text="form.errors.get('quantity')"
          ></span>
        </transition>
      </div>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('periodo_ordinativi') }">
        <label>{{trans('modRicFat.periodo')}}</label>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="periodo_ordinativi"
          color="info"
          v-model="form.periodo_ordinativi"
          value="Mensile"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          {{trans('modRicFat.mensile')}}
        </pRadio>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="periodo_ordinativi"
          color="info"
          v-model="form.periodo_ordinativi"
          value="Trimestrale"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          {{trans('modRicFat.trimestreale')}}
        </pRadio>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="periodo_ordinativi"
          color="info"
          v-model="form.periodo_ordinativi"
          value="Annuale"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          {{trans('modRicFat.annuale')}}
        </pRadio>
      </div>
      
      <hr>
      <div class="form-group has-feedback">
        <label>{{trans('modRicFat.pack_ask')}}</label>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="isSpecialPack"
          color="info"
          v-model="isSpecialPack"
          value=true
        >
          <i class="icon fa fa-check" slot="extra"></i>
          {{trans('modRicFat.answer_yes')}}
        </pRadio>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="isSpecialPack"
          color="info"
          v-model="isSpecialPack"
          value=false
        >
          <i class="icon fa fa-check" slot="extra"></i>
          {{trans('modRicFat.answer_no')}}
        </pRadio>
      </div>
      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('imballaggio') }" v-show="isSpecialPack==true">
        <label>{{trans('modRicFat.pack_descr')}}</label>
        <textarea
          class="form-control"
          name='descr_pers'
          rows="5"
          v-model="form.imballaggio"
          :placeholder="trans('modRicFat.insertNote')"
        ></textarea>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('imballaggio')"
            v-text="form.errors.get('imballaggio')"
          ></span>
        </transition>
      </div>
    </boxDefault>

    <!-- TARGET PRICE -->
    <boxDefault header=true v-show="isStep5End && isStep5End!=null">
      <template v-slot:header>
        {{trans('modRicFat.target_price')}}
      </template>
      
      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('target_price') }">
        <label>{{trans('modRicFat.price')}}</label>
        <money 
          v-model="form.target_price"
          v-bind="money"
          class="form-control"
          name="target_price"
        />
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('target_price')"
            v-text="form.errors.get('target_price')"
          ></span>
        </transition>
      </div>

    </boxDefault>

    <button type="submit" class="btn btn-success btn-block" :disabled="form.errors.any() || form.submitting" v-show="isTheEnd">
      <i v-if="form.submitting" class="fa fa-refresh fa-spin"></i>
      {{trans('modRicFat.submit')}}
    </button>
  </form>
</template>

<script>
import Form from "acacha-forms";
import redirect from "../redirect";
import pInput from "pretty-checkbox-vue/input";
import pCheck from "pretty-checkbox-vue/check";
import pRadio from "pretty-checkbox-vue/radio";
import vSelect from "vue-select";
import vMultiSelect from "vue-multiselect";
import boxDefault from "./layouts/BoxDefault";
import formGroup from "./layouts/FormGroup";
import { Money } from 'v-money'

export default {
  props: ["contact", "sysmkt", "sysother", "ditta"],
  mixins: [redirect],

  data() {
    return {
      form: new Form({
        data_ricezione: '',
        richiedente: '',
        email_richiedente: '',
        ragione_sociale: JSON.parse(this.contact).descrizion,
        codicecf: JSON.parse(this.contact).codice,
        tipologia_prodotto: '',
        descr_pers: '',
        url_pers: '',
        system_kk: '',
        system_other: '',
        info_tecn_comm: '',
        imballaggio: '',
        um: '',
        quantity: '',
        periodo_ordinativi: '',
        target_price: 0.00,
        ditta: this.ditta
      }),
      money: {
          decimal: ',',
          thousands: '.',
          prefix: '',
          suffix: ' €',
          precision: 2,
          masked: false
        },
      isStep1End: null,
      isStep2End: null,
      isStep3End: null,
      isStep4End: null,
      isStep5End: null,
      isStep6End: null, 
      isTheEnd: false,  
      isRifProdKK: 0,   
      isSpecialPack: null,
      optionsUM: [{codice: 'PZ',descrizione: 'Pezzi'},{codice: 'CF',descrizione: 'Confezioni'}],
      optionsSysMkt: JSON.parse(this.sysmkt),
      optionsSysOther: JSON.parse(this.sysother),
      preSysKnown: [{ codice: "", descrizione: "none" }],
      reg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/
    };
  },

  compute: {},

  components: {
    pCheck,
    pRadio,
    vSelect,
    vMultiSelect,
    boxDefault,
    formGroup,
    Money
  },

  methods: {
    checkGoOn(){
      let lGoOn = true;
      //STEP 1
      if(!_.isEmpty(this.form.email_richiedente)){
        if(_.isEmpty(this.form.ragione_sociale)) {
          this.form.errors.set({errors: {'ragione_sociale': 'Deve Essere Inserita la Ragione Sociale'}});
          lGoOn = false;
        }
        if(_.isEmpty(this.form.richiedente)) {
          this.form.errors.set({errors: {'richiedente': 'Inserire Nome e Cognome Richiedente'}});
          lGoOn = false;
        }
        if(!this.reg.test(this.form.email_richiedente)){
          this.form.errors.set({errors: {'email_richiedente': 'Deve Essere una Email Valida'}});
          lGoOn = false;
        }
        if(lGoOn) this.isStep1End=true;
      }
      //STEP 2
      if(!_.isEmpty(this.form.descr_pers)){
        if(_.isEmpty(this.form.tipologia_prodotto)) {
          this.form.errors.set({errors: {'tipologia_prodotto': 'Selezionare una Tipologia Prodotto'}});
          lGoOn = false;
        }
        if(lGoOn) this.isStep2End=true;
      }
      //STEP 3
      if(this.isRifProdKK!=0){
        if(this.isRifProdKK==1){
          this.form.system_other='';
          if(_.isEmpty(this.form.system_kk)) {
            this.form.errors.set({errors: {'system_kk': 'Selezionare una Tipologia Prodotto KK'}});
            lGoOn = false;
          }
        }
        if(this.isRifProdKK==2){
          this.form.system_kk='';
          if(_.isEmpty(this.form.system_other)) {
            this.form.errors.set({errors: {'system_other': 'Selezionare una Tipologia Prodotto'}});
            lGoOn = false;
          }
        }
        if(this.isRifProdKK==3){
          this.form.system_other='';
          this.form.system_kk='';
        }
        if(lGoOn) this.isStep3End=true;
      }
      //STEP 4
      if(!_.isEmpty(this.form.info_tecn_comm)){
        if(lGoOn) this.isStep4End=true;
      }
      //STEP 5
      if(this.isSpecialPack!=null){        
        if(_.isEmpty(this.form.um)) {
          this.form.errors.set({errors: {'um': 'Selezionare Unità di Misura'}});
          lGoOn = false;
        }        
        if(_.isEmpty(this.form.quantity)) {
          this.form.errors.set({errors: {'quantity': 'Inserire Quantità Prevista di approvigionamento'}});
          lGoOn = false;
        } 
        if(_.isEmpty(this.form.periodo_ordinativi)) {
          this.form.errors.set({errors: {'periodo_ordinativi': 'Selezionare una Periodicità Prevista di approvigionamento'}});
          lGoOn = false;
        }
        if(this.isSpecialPack==true && _.isEmpty(this.form.imballaggio)) {
          this.form.errors.set({errors: {'imballaggio': 'Inserire descrizione Imballaggio Personalizzato'}});
          lGoOn = false;
        }
        if(lGoOn) this.isStep5End=true;
      }
      //STEP 6
      if(this.form.target_price!=0.00){
        if(lGoOn) this.isTheEnd=true;
      }
      return lGoOn;
    },

    personalEventSelect(name){
      this.form.errors.clear(name);
      this.checkGoOn();
    },

    onSubmit() {
      if(this.checkGoOn()){
        this.form.post("/storeRicFatt")
        .then(response => {
          alert('Modulo Salvato! Verrai reindirizzato...');
          window.location.replace('/client/'+JSON.parse(this.contact).codice);
        }).catch(error => {
          alert("C'è Stato un errore! Contattare Assistenza!");
        });
      }
    }
  }
};
</script>

<style>
@import "~/plugins/pretty-checkbox/css/pretty-checkbox.min.css";
@import "~/plugins/vue-multiselect/css/vue-multiselect.min.css";
</style>
