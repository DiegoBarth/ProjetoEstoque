
<template>
   <div class="card-principal w-[calc(100vw-50px)] h-[calc(100vh-50px)] m-[25px] rounded-xl overflow-hidden">
      <div class="grid gap-6 p-4 grid-cols-2 w-full h-full rounded-xl overflow-auto">
         <div
            v-for="(oMeta) in aMetas"
            :key="oMeta.iCodigo"
            :class="{
               'col-span-2 flex justify-center': aMetas.length === 1,
               'col-span-1': aMetas.length > 1,
               'card-principal rounded-sm': aMetas.length > 1
            }"
         >
            <component :is="getComponent(oMeta.iTipo)" :dados="oMeta" />
         </div>
      </div>
   </div>
</template>

<script>
   import MetaTipo1 from '@/components/Meta/MetaTipo1.vue';
   import MetaTipo2 from '@/components/Meta/MetaTipo2.vue';
   import MetaTipo3 from '@/components/Meta/MetaTipo3.vue';
   import MetaTipo4 from '@/components/Meta/MetaTipo4.vue';
   import MetaTipo5 from '@/components/Meta/MetaTipo5.vue';
   import MetaTipo6 from '@/components/Meta/MetaTipo6.vue';
   import MetaTipo7 from '@/components/Meta/MetaTipo7.vue';
   import MetaTipo8 from '@/components/Meta/MetaTipo8.vue';
   import MetaTipo9 from '@/components/Meta/MetaTipo9.vue';
   import { useMetaStore } from '@/stores/metaStore';

   export default {
      name: 'MetasGraficos',
      components: { MetaTipo1, MetaTipo2, MetaTipo3, MetaTipo4, MetaTipo5, MetaTipo6, MetaTipo7, MetaTipo8, MetaTipo9 },
      data() {
         return {
            aMetas: []
         }
      },
      mounted() {
         const oMetaStore = useMetaStore();

         this.buscarMetas(oMetaStore);
      },
      methods: {
         async buscarMetas(store) {
            this.aMetas = await store.getResultadosMetas();
         },
         getComponent(tipo) {
            return `MetaTipo${tipo}`
         }
      }
   }
</script>

<style scoped>
</style>