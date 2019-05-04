<template>
    <div class="lds-dual-ring"></div>
</template>

<style>
    .lds-dual-ring {
        display: inline-block;
        width: 32px;
        height: 32px;
        position: absolute;
        margin-left: 10px;
        margin-top: 4px;
    }
    .lds-dual-ring:after {
        content: " ";
        display: block;
        width: 23px;
        height: 23px;
        margin: 1px;
        border-radius: 50%;
        border: 5px solid #000;
        border-color: #000 transparent #000 transparent;
        animation: lds-dual-ring 1.2s linear infinite;
    }
    @keyframes lds-dual-ring {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

</style>