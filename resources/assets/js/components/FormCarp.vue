<template>
  <form method="POST" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)" @change='checkGoOn'>
    <boxDefault>
      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('ragioneSociale') }"
      >
        <label>Ragione Sociale</label>
        <input
          type="text"
          class="form-control"
          name="ragioneSociale"
          v-model="form.ragioneSociale"
          disabled
        >
      </div>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('typeProd') }" @change="form.errors.clear($event.target.name)">
        <label>Tipologia di Produzione</label>
        <div>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="typeProd"
            v-model="form.typeProdPorte"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Porte
          </pCheck>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="typeProd"
            v-model="form.typeProdFinestre"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Finestre
          </pCheck>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="typeProd"
            v-model="form.typeProdMobili"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Mobili
          </pCheck>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="typeProd"
            v-model="form.typeProdCucine"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Cucine
          </pCheck>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="typeProd"
            v-model="form.typeProdOther"
            @change="clearNoteProd"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Altro
          </pCheck>
        </div>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('typeProd')"
            v-text="form.errors.get('typeProd')"
          ></span>
        </transition>
      </div>

      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('noteProdOther') }"
        v-show="form.typeProdOther"
      >
        <label>Descrivi Altra Tipologia di Produzione...</label>
        <textarea
          class="form-control"
          rows="5"
          v-model="form.noteProdOther"
          name="noteProdOther"
          placeholder="Inserisci Note"
        ></textarea>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('noteProdOther')"
            v-text="form.errors.get('noteProdOther')"
          ></span>
        </transition>
        <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="typeProd"
            v-model="form.isTermina"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Termina Questionario
          </pCheck>
      </div>

      <hr>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('rConosceKK') }">
        <label>Conosce Krona Koblenz?</label>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="rConosceKK"
          color="info"
          v-model="form.rConosceKK"
          value="true"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          Sì
        </pRadio>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="rConosceKK"
          color="info"
          v-model="form.rConosceKK"
          value="false"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          No
        </pRadio>
      </div>
    </boxDefault>

    <!-- QUI CONOSCE KK  -->
    <boxDefault v-show="isConosceKK && isConosceKK!=null">
      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('sysKnown') }">
        <label>Quali Prodotti di Krona Koblenz Conosce?</label>
        <v-multi-select
          v-model="form.sysKnown"
          :options="optionsSysMkt"
          :multiple="true"
          :searchable="true"
          placeholder="Pick a value"
          label="descrizione"
          track-by="codice"
          @close="personalEventSelect('sysKnown')"
        ></v-multi-select>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('sysKnown')"
            v-text="form.errors.get('sysKnown')"
          ></span>
        </transition>
      </div>
      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('rAcquistaKK') }">
        <label>Acquisto i Prodotti Krona Koblenz?</label>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="rAcquistaKK"
          color="info"
          v-model="form.rAcquistaKK"
          value="true"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          Sì
        </pRadio>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="rAcquistaKK"
          color="info"
          v-model="form.rAcquistaKK"
          value="false"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          No
        </pRadio>
      </div>
    </boxDefault>

    <!-- QUI CONOSCE KK E ACQUISTA KK  -->
    <boxDefault v-show="(isAcquistaKK && isAcquistaKK!=null)">
      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('sysBuyOfKK') }">
        <label>Quali Prodotti di Krona Koblenz Acquista?</label>
        <v-multi-select
          v-model="form.sysBuyOfKK"
          :options="preSysKnown"
          :multiple="true"
          :searchable="true"
          placeholder="Pick a value"
          label="descrizione"
          track-by="codice"
          @select="personalEventSelect('sysBuyOfKK')"
        ></v-multi-select>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('sysBuyOfKK')"
            v-text="form.errors.get('sysBuyOfKK')"
          ></span>
        </transition>
      </div>
      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('yes_supplierType') }"
         @change="form.errors.clear($event.target.name)"
      >
        <label>Da chi acquista?</label>
        <div>
          <pRadio
            class="p-icon p-round p-fill p-smooth p-bigger"
            name="yes_supplierType"
            color="info"
            v-model="form.yes_supplierType"
            value="1"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Ferramenta
          </pRadio>
          <pRadio
            class="p-icon p-round p-fill p-smooth p-bigger"
            name="yes_supplierType"
            color="info"
            v-model="form.yes_supplierType"
            value="2"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Gruppo Commerciale
          </pRadio>
        </div>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('yes_supplierType')"
            v-text="form.errors.get('yes_supplierType')"
          ></span>
        </transition>
      </div>
      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('yes_supplierName') }"
        v-show="form.yes_supplierType>1"
      >
        <label>Ragione Sociale del Fornitore</label>
        <input type="text" class="form-control" name="yes_supplierName" v-model="form.yes_supplierName">
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('yes_supplierName')"
            v-text="form.errors.get('yes_supplierName')"
          ></span>
        </transition>
      </div>

      <hr>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('yes_isInformato') }">
        <label>Viene regolarmente informato sui Nuovi Prodotti di Krona Koblenz?</label>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="yes_isInformato"
          color="info"
          v-model="form.yes_isInformato"
          value="true"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          Sì
        </pRadio>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="yes_isInformato"
          color="info"
          v-model="form.yes_isInformato"
          value="false"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          No
        </pRadio>
      </div>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('wants_info') }" v-show='form.yes_isInformato=="false"'>
        <hr>
        <label>Vuole ricevere Documentazioni?</label>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="wants_info"
          color="info"
          v-model="form.wants_info"
          value="true"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          Sì
        </pRadio>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="wants_info"
          color="info"
          v-model="form.wants_info"
          value="false"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          No
        </pRadio>
      </div>
    </boxDefault>

    <!-- QUI NON CONOSCE KK  -->
    <boxDefault v-show="(!isConosceKK && isConosceKK!=null) || (!isAcquistaKK && isAcquistaKK!=null)">
      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('sysBuyOfOther') }"
      >
        <label>Quali Prodotti della Concorrenza Utilizza?</label>
        <v-multi-select
          v-model="form.sysBuyOfOther"
          :options="optionsSysOther"
          :multiple="true"
          :searchable="true"
          placeholder="Pick a value"
          label="descrizione"
          track-by="codice"
          @close="personalEventSelect('sysBuyOfOther')"
        ></v-multi-select>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('sysBuyOfOther')"
            v-text="form.errors.get('sysBuyOfOther')"
          ></span>
        </transition>
      </div>

      <hr>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('notWhy') }"  @change="form.errors.clear($event.target.name)">
        <label>Cosa le ha fatto scegliere questi prodotti?</label>
        <div>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="notWhy"
            v-model="form.not_why_noinfo"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Non Conosco Krona Koblenz
          </pCheck>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="notWhy"
            v-model="form.not_why_catalogo"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Catalogo
          </pCheck>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="notWhy"
            v-model="form.not_why_prezzo"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Prezzo
          </pCheck>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="notWhy"
            v-model="form.not_why_qualita"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Qualità
          </pCheck>
          <pCheck
            class="p-icon p-curve p-smooth p-bigger"
            color="info-o"
            name="notWhy"
            v-model="form.not_why_servizio"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Servizio
          </pCheck>
        </div>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('notWhy')"
            v-text="form.errors.get('notWhy')"
          ></span>
        </transition>
      </div>

      <hr>

      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('not_supplierType') }" 
        @change="form.errors.clear($event.target.name)"
      >
        <label>Da chi acquista?</label>
        <div>
          <pRadio
            class="p-icon p-round p-fill p-smooth p-bigger"
            name="not_supplierType"
            color="info"
            v-model="form.not_supplierType"
            value="1"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Ferramenta
          </pRadio>
          <pRadio
            class="p-icon p-round p-fill p-smooth p-bigger"
            name="not_supplierType"
            color="info"
            v-model="form.not_supplierType"
            value="2"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Gruppo Commerciale
          </pRadio>
          <pRadio
            class="p-icon p-round p-fill p-smooth p-bigger"
            name="not_supplierType"
            color="info"
            v-model="form.not_supplierType"
            value="3"
          >
            <i class="icon fa fa-check" slot="extra"></i>
            Direttamente dal Produttore
          </pRadio>
        </div>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('not_supplierType')"
            v-text="form.errors.get('not_supplierType')"
          ></span>
        </transition>
      </div>
      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('not_supplierName') }"
        v-show="form.not_supplierType>1"
      >
        <label>Ragione Sociale del Fornitore</label>
        <input type="text" class="form-control" name="not_supplierName" v-model="form.not_supplierName">
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('not_supplierName')"
            v-text="form.errors.get('not_supplierName')"
          ></span>
        </transition>
      </div>

      <hr>

      <div class="form-group has-feedback" :class="{ 'has-error': form.errors.has('rConosceKK') }">
        <label>Ha intenzione di provare i Prodotti Krona Koblenz?</label>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="wants_tryKK"
          color="info"
          v-model="form.wants_tryKK"
          value="true"
          @change="boolWantsTryKK"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          Sì
        </pRadio>
        <pRadio
          class="p-icon p-round p-fill p-smooth p-bigger"
          name="wants_tryKK"
          color="info"
          v-model="form.wants_tryKK"
          value="false"
          @change="boolWantsTryKK"
        >
          <i class="icon fa fa-check" slot="extra"></i>
          No
        </pRadio>
      </div>

      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('sysLiked') }"
        v-show="isTryKK && isTryKK!=null"
      >
        <label>Quali Sistemi Le interessano maggiormente?</label>
        <v-multi-select
          v-model="form.sysLiked"
          :options="optionsSysMkt"
          :multiple="true"
          :searchable="true"
          placeholder="Pick a value"
          label="descrizione"
          track-by="codice"
          @close="personalEventSelect('sysLiked')"
        ></v-multi-select>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('sysLiked')"
            v-text="form.errors.get('sysLiked')"
          ></span>
        </transition>
      </div>

      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('notryKK_note') }"
        v-show="!isTryKK && isTryKK!=null"
      >
        <label>Motiva la scelta...</label>
        <textarea
          class="form-control"
          rows="5"
          name="notryKK_note"
          v-model="form.notryKK_note"
          placeholder="Inserisci Note"
        ></textarea>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('notryKK_note')"
            v-text="form.errors.get('notryKK_note')"
          ></span>
        </transition>
      </div>
    </boxDefault>

    <box-default v-show="isTheEnd">
      <div
        class="form-group has-feedback"
        :class="{ 'has-error': form.errors.has('final_note') }"
      >
        <label>Note Finali</label>
        <textarea
          class="form-control"
          name='final_note'
          rows="5"
          v-model="form.final_note"
          placeholder="Inserisci Note"
        ></textarea>
        <transition name="fade">
          <span
            class="help-block"
            v-if="form.errors.has('final_note')"
            v-text="form.errors.get('final_note')"
          ></span>
        </transition>
      </div>
    </box-default>
    
    <button type="submit" class="btn btn-success btn-block" :disabled="form.errors.any() || form.submitting" v-show="isTheEnd">
      <i v-if="form.submitting" class="fa fa-refresh fa-spin"></i>
      Salva
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

export default {
  props: ["contact", "sysmkt", "sysother"],
  mixins: [redirect],

  data() {
    return {
      form: new Form({
        ragioneSociale: JSON.parse(this.contact).descrizion,
        rubri_id: JSON.parse(this.contact).id,
        typeProdPorte: false,
        typeProdFinestre: false,
        typeProdCucine: false,
        typeProdMobili: false,
        typeProdOther: false,
        noteProdOther: "",
        isTermina: false,
        rConosceKK: null,
        rAcquistaKK: null,
        sysKnown: "",
        sysBuyOfKK: "",
        sysBuyOfOther: "",
        sysLiked: "",
        yes_supplierType: "",
        yes_supplierName: "",
        yes_isInformato: null,
        not_why_prezzo: false,
        not_why_qualita: false,
        not_why_servizio: false,
        not_why_catalogo: false,
        not_why_noinfo: false,
        not_supplierType: "",
        not_supplierName: "",
        wants_tryKK: null,
        notryKK_note: "",
        wants_info: null,
        final_note: "",
        vote: 0
      }),
      isConosceKK: null,
      oldConosceKK: null,
      isAcquistaKK: null,
      oldAcquistaKK: null,
      isTryKK: null,
      oldTryKK: null,
      isTheEnd: false,
      optionsSysMkt: JSON.parse(this.sysmkt),
      optionsSysOther: JSON.parse(this.sysother),
      preSysKnown: [{ codice: "", descrizione: "none" }]
    };
  },

  compute: {},

  components: {
    pCheck,
    pRadio,
    vSelect,
    vMultiSelect,
    boxDefault
  },

  methods: {
    
    checkGoOn(){
      let lGoOn = true;
      //STEP 1
      if(this.form.rConosceKK!=null){
        if(!this.form.typeProdPorte && !this.form.typeProdFinestre && !this.form.typeProdCucine && !this.form.typeProdMobili && !this.form.typeProdOther) {
          this.form.errors.set({errors: {'typeProd': 'Deve Essere selezionato almeno una Tipologia di Produzione'}});
          lGoOn = false;
        }
        if(this.form.typeProdOther && _.isEmpty(this.form.noteProdOther)) {
          this.form.errors.set({errors: {'noteProdOther': 'Deve Essere Descritta la Tipologia'}});
          lGoOn = false;
        }
        if(lGoOn && this.oldConosceKK != this.form.rConosceKK){
          this.boolConosceKK();
        }
      }
      if(!this.form.isTermina){
        //STEP 2a Conosce KK
        if(this.isConosceKK && this.form.rAcquistaKK!=null){
          lGoOn = true;
          if(_.isEmpty(this.form.sysKnown)){
            this.form.errors.set({errors: {'sysKnown': 'Deve essere selezionata almeno una voce'}});
            lGoOn = false;
          }
          if(lGoOn && this.oldAcquistaKK != this.form.rAcquistaKK){
            this.boolAcquistaKK();
          }
        }
        //STEP 3a Conosce e Acquista KK
        if(this.isConosceKK && this.isAcquistaKK && this.form.yes_isInformato!=null){
          if(_.isEmpty(this.form.sysBuyOfKK)){
            this.form.errors.set({errors: {'sysBuyOfKK': 'Deve essere selezionata almeno una voce'}});
            lGoOn = false;
          } else if(this.form.yes_supplierType==0){
            this.form.errors.set({errors: {'yes_supplierType': 'Seleziona una tipologia di Fornitore'}});
            lGoOn = false;
          } else if(this.form.yes_supplierType>1 && _.isEmpty(this.form.yes_supplierName)){
            this.form.errors.set({errors: {'yes_supplierName': 'Inserire Ragione Sociale Fornitore'}});
            lGoOn = false;
          }
        }
        if(lGoOn){
          if(this.form.yes_isInformato=='true'){
            this.isTheEnd = true;
          } else {
            if(this.form.yes_isInformato=='false' && this.form.wants_info!=null){
              this.isTheEnd = true;
            }
          }
        }
        //STEP 2b/3b NON Conosce o NON Acquista KK
        if((!this.isConosceKK || !this.isAcquistaKK) && this.form.wants_tryKK!=null){
          console.log('Step2b/3b');
          if(_.isEmpty(this.form.sysBuyOfOther)){
            this.form.errors.set({errors: {'sysBuyOfOther': 'Deve essere selezionata almeno una voce'}});
            lGoOn = false;
          } else if(!this.form.not_why_prezzo && !this.form.not_why_qualita && !this.form.not_why_servizio && !this.form.not_why_catalogo && !this.form.not_why_noinfo){
            this.form.errors.set({errors: {'notWhy': 'Specificare una voce'}});
            lGoOn = false;
          } else if(this.form.not_supplierType==0){
            this.form.errors.set({errors: {'not_supplierType': 'Seleziona una tipologia di Fornitore'}});
            lGoOn = false;
          } else if(this.form.not_supplierType>1 && _.isEmpty(this.form.not_supplierName)){
            this.form.errors.set({errors: {'not_supplierName': 'Inserire Ragione Sociale Fornitore'}});
            lGoOn = false;
          }    
        }    
        if(lGoOn){
          if(this.form.wants_tryKK=='true' && !_.isEmpty(this.form.sysLiked)){
            this.isTheEnd = true;
          } else if (this.form.wants_tryKK=='true' && _.isEmpty(this.form.sysLiked)) {
            this.form.errors.set({errors: {'sysLiked': 'Deve essere selezionata almeno una voce'}});
            lGoOn = false;
          }
          
          if(this.form.wants_tryKK=='false' && this.form.notryKK_note!=''){
            this.isTheEnd = true;
          } else if(this.form.wants_tryKK=='false' && this.form.notryKK_note==''){
            this.form.errors.set({errors: {'notryKK_note': 'Specificare il perchè'}});
            lGoOn = false;
          }
        }
      } else {
        this.isTheEnd = true;
      }
      return lGoOn;
    },

    clearNoteProd() {
      if (!this.form.typeProdOther) {
        this.form.noteProdOther = "";
        this.form.isTermina = false;
        this.isTheEnd = false;
        this.form.errors.clear('noteProdOther');
      }
    },

    boolConosceKK() {
      this.oldConosceKK = this.form.rConosceKK;
      this.form.rAcquistaKK = null;
      this.form.sysKnown = "";
      this.form.sysBuyOfKK = "";
      this.form.sysBuyOfOther = "";
      this.form.sysLiked = "";
      this.form.yes_supplierType = "";
      this.form.yes_supplierName = "";
      this.form.yes_isInformato = null;
      this.form.not_why_prezzo = false;
      this.form.not_why_qualita = false;
      this.form.not_why_servizio = false;
      this.form.not_why_catalogo = false;
      this.form.not_why_noinfo = false;
      this.form.not_supplierType = "";
      this.form.not_supplierName = "";
      this.form.wants_tryKK = null;
      this.form.notryKK_note = "";
      this.form.wants_info = null;
      this.form.final_note = "";
      this.form.vote = 0;
      this.isAcquistaKK = null;
      this.oldAcquistaKK = null;
      this.isTryKK = null;
      this.oldTryKK = null;
      this.isTheEnd = false;
      this.isConosceKK = this.form.rConosceKK === "true" ? true : false;
    },

    boolAcquistaKK() {
      this.oldAcquistaKK = this.form.rAcquistaKK;
      this.form.sysBuyOfKK = "";
      this.form.sysBuyOfOther = "";
      this.form.sysLiked = "";
      this.form.yes_supplierType = "";
      this.form.yes_supplierName = "";
      this.form.yes_isInformato = null;
      this.form.not_why_prezzo = false;
      this.form.not_why_qualita = false;
      this.form.not_why_servizio = false;
      this.form.not_why_catalogo = false;
      this.form.not_why_noinfo = false;
      this.form.not_supplierType = "";
      this.form.not_supplierName = "";
      this.form.wants_tryKK = null;
      this.form.notryKK_note = "";
      this.form.wants_info = null;
      this.form.final_note = "";
      this.form.vote = 0;
      this.isTryKK = null;
      this.oldTryKK = null;
      this.isTheEnd = false;
      this.isAcquistaKK = this.form.rAcquistaKK === "true" ? true : false;
    },

    boolWantsTryKK() {
      this.isTryKK = this.form.wants_tryKK === "true" ? true : false;
    },

    listSysKnown() {
      if (!_.isEmpty(this.form.sysKnown)){
        this.preSysKnown = this.form.sysKnown;
      }
    },

    personalEventSelect(name){
      this.form.errors.clear(name);
      if(name=='sysKnown') this.listSysKnown();
      this.checkGoOn();
    },

    onSubmit() {
      if(this.checkGoOn()){
        this.form.post("/storeModCarp01")
        .then(response => {
          alert('Modulo Salvato! Verrai reindirizzato...');
          window.location.replace('/contact/'+JSON.parse(this.contact).id);
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
