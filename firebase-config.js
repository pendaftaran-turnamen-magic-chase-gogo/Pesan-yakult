import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getDatabase, ref, set, push, onValue, update, remove, serverTimestamp, get, child } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js";

const firebaseConfig = {
    apiKey: "AIzaSyDS8eF2L1iOVo61p6lcf7BrH1xOEjFzXOU",
    authDomain: "pesan-yakult.firebaseapp.com",
    databaseURL: "https://pesan-yakult-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "pesan-yakult",
    storageBucket: "pesan-yakult.firebasestorage.app",
    messagingSenderId: "300443740751",
    appId: "1:300443740751:android:11de10105e7c0cfc5fd83a"
};

const app = initializeApp(firebaseConfig);
const db = getDatabase(app);

export { db, ref, set, push, onValue, update, remove, serverTimestamp, get, child };
