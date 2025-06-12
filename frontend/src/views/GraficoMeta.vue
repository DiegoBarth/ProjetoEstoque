
<template>
   <div class="card-principal w-[calc(100vw-50px)] h-[calc(100vh-50px)] m-[25px] rounded-xl overflow-hidden">
      <div class="grid gap-6 p-4 grid-cols-2 w-full h-full rounded-xl overflow-auto">
         <div
  v-for="(meta, index) in aMetas"
  :key="meta.iCodigo"
  :class="{
    'col-span-2 flex justify-center': aMetas.length === 1,
    'col-span-1': aMetas.length > 1
  }"
>
  <component :is="getComponent(meta.iTipo)" :dados="meta" />
</div>
      </div>
   </div>
</template>

<script>
   import MetaTipo1 from '@/components/Meta/MetaTipo1.vue';
   import MetaTipo2 from '@/components/Meta/MetaTipo2.vue';
   import MetaTipo3 from '@/components/Meta/MetaTipo3.vue';
   import api from "@/api";

   export default {
      name: 'MetasGraficos',
      components: {
         MetaTipo1,
         MetaTipo2,
         MetaTipo3,
      },
      data() {
         return {
            aMetas: []
         }
      },
      mounted() {
         this.buscarMetas()
      },
      methods: {
         async buscarMetas() {
            try {
               const oRetorno = await api.get('/api/meta/consulta');
               this.aMetas = oRetorno.data;
            }
            catch(error) {
               console.error('Erro ao buscar metas:', error);
            }
         },
         getComponent(tipo) {
            return `MetaTipo${tipo}`
         }
      }
   }
</script>

<style scoped>
</style>