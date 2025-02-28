import { initializeApp } from 'firebase/app';
import { getFirestore, collection, addDoc, query, where, getDocs } from 'firebase/firestore';

// Firebase configuration using environment variables
const firebaseConfig = {
    apiKey: process.env.FIREBASE_API_KEY,
    authDomain: process.env.FIREBASE_AUTH_DOMAIN,
    projectId: process.env.FIREBASE_PROJECT_ID,
    storageBucket: process.env.FIREBASE_STORAGE_BUCKET,
    messagingSenderId: process.env.FIREBASE_MESSAGING_SENDER_ID,
    appId: process.env.FIREBASE_APP_ID,
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);

export default async function handler(req, res) {
    if (req.method === 'POST') {
        const { userAgent } = req.body;

        try {
            // Check if the user agent already exists
            const q = query(collection(db, 'userAgents'), where('userAgent', '==', userAgent));
            const querySnapshot = await getDocs(q);

            if (querySnapshot.empty) {
                // Insert the user agent if it doesn't exist
                await addDoc(collection(db, 'userAgents'), { userAgent });
                res.status(200).json({ message: 'User agent saved.' });
            } else {
                res.status(200).json({ message: 'User agent already exists.' });
            }
        } catch (error) {
            console.error('Error:', error);
            res.status(500).json({ error: 'Failed to save user agent.' });
        }
    } else {
        res.status(405).json({ error: 'Method not allowed.' });
    }
}
