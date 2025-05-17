<template>
    <div class="flex w-full h-full">
        <div class="cardLogin z-1 card bg-white w-2/3 h-full items-center justify-center flex">
            <div class="card bg-white shadow-xl h-1/2 w-2/4 items-center justify-center flex flex-col rounded-2xl" style="box-shadow: 0px 0px 50px lightgray">
                <div class="flex items-center gap-5 py-7">
                    <img src="../assets/img/logo1.png" class="h-25" alt="">
                    <i class="fa fa-plus text-4xl"></i>
                    <img src="../assets/img/logo2.png" class="h-25" alt="">
                </div>                
                <div class="flex flex-col gap-2">
                    <input class="border-1 rounded-sm w-xs" style="padding: 3px; border-color: #E0E0E0; outline: none;" type="text" name="email" placeholder="Email"/>
                    <input class="border-1 rounded-sm w-xs" style="padding: 3px; border-color: #E0E0E0; outline: none;" type="text" name="senha" placeholder="Senha"/>
                    <input @click="() => router.push({ name: 'Inicio' })" type="submit" name="enviar" value="enviar" style="background-color: #7ac1e3; padding: 3px" class="w-20 rounded"/>
                </div>
            </div>
        </div>        
        <img src="../assets/img/fundoLogin.png" class="w-1/3 z-0">
    </div>
</template>
<script setup>
import { useRouter } from 'vue-router'

const router = useRouter();
</script>
<style scoped>
.cardLogin {
    box-shadow: 10px 0px 100px rgb(19, 19, 19);
}
</style>
