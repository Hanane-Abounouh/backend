import Echo from 'laravel-echo';
import Pusher from 'pusher-js'; // Importez Pusher de cette manière

// Configurez Laravel Echo
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY, // Assurez-vous que cela correspond à votre .env
    cluster: process.env.MIX_PUSHER_APP_CLUSTER, // Assurez-vous que cela correspond à votre .env
    encrypted: true
});

// Remplacez 'tâcheId' et 'projetId' par les variables appropriées dans votre code
const tâcheId = 1; // Changez cela selon le contexte où vous utilisez echo.js
const projetId = 1; // Changez cela selon le contexte de votre projet

// Écoutez les événements sur le canal spécifié pour les tâches
window.Echo.channel('tâche.' + tâcheId)
    .listen('TâcheAssignée', (event) => {
        console.log('Tâche assignée:', event.tâche); // Gérer l'événement de tâche ici
    });

// Écoutez les événements sur le canal spécifié pour les invitations de projet
window.Echo.channel('projet.' + projetId)
    .listen('InvitationProjet', (event) => {
        console.log('Invitation au projet:', event.projet); // Gérer l'événement d'invitation ici
    });
