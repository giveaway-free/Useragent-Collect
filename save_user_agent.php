import { MongoClient } from 'mongodb';

export default async function handler(req, res) {
    if (req.method === 'POST') {
        const { userAgent } = req.body;

        // Connect to MongoDB
        const uri = process.env.MONGODB_URI; // Add your MongoDB URI in Vercel environment variables
        const client = new MongoClient(uri, { useNewUrlParser: true, useUnifiedTopology: true });

        try {
            await client.connect();
            const database = client.db('userAgents');
            const collection = database.collection('agents');

            // Check if the user agent already exists
            const existingAgent = await collection.findOne({ userAgent });
            if (!existingAgent) {
                // Insert the user agent if it doesn't exist
                await collection.insertOne({ userAgent });
                res.status(200).json({ message: 'User agent saved.' });
            } else {
                res.status(200).json({ message: 'User agent already exists.' });
            }
        } catch (error) {
            res.status(500).json({ error: 'Failed to save user agent.' });
        } finally {
            await client.close();
        }
    } else {
        res.status(405).json({ error: 'Method not allowed.' });
    }
}
