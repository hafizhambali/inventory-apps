import { initFlowbite } from 'flowbite';
import './bootstrap';
import 'flowbite';

document.addEventListener('livewire:navigated' , () => {
    initFlowbite()
    console.log('navigated')
})

document.addEventListener('livewire:navigating' , () => {
    console.log('navigating')
})
