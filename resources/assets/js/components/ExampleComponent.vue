<template>
    <div class="modalwindow">
        <h1>Example</h1>
        <p>Some random text that afna apfgaj ea eg eag.</p>
        <button>Close me</button>
        asdasd
        
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

<style>
.modalwindow {
  background: cyan;
  color: black;
  padding: 20px;
  width: 200px;
  top: 20%;
  left: 20%;
  width: 60%;
  min-height: 60%;
  position: fixed;
  z-index: 10;
}    
</style>
