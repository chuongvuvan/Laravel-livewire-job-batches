import './bootstrap';
import confetti from "canvas-confetti";

Livewire.on('confetti', () => {
    confetti({
        particleCount: 80,
        spread: 200,
        origin: {y: 0.6}
    });
})
