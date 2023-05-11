import React, { useState } from 'react';
import axios from '@/lib/axios'
import Link from 'next/link';

const TopicsTable = ({ topics, onTopicCreated }) => {
    const [newTopicTitle, setNewTopicTitle] = useState('');
    const [newTopicDescription, setNewTopicDescription] = useState('');


    const deleteTopic = (id) => {
        axios.delete('/api/topics/' + id)
            .then(response => {
                console.log(response.data)
                onTopicCreated();
            })
            .catch(error => console.log(error));
    }

    const handleNewTopicSubmit = (event) => {
        event.preventDefault();

        const newTopic = {
            title: newTopicTitle,
            description: newTopicDescription
        };

        axios.post('/api/topics', newTopic)
            .then(response => {
                console.log(response.data);
                setNewTopicTitle('');
                setNewTopicDescription('');
                onTopicCreated();
            })
            .catch(error => console.log(error));
    };

    return (
        <div className="bg-white p-6 rounded-lg shadow-md">
            <h1 className="text-2xl font-bold mb-4">Topics</h1>
            <form onSubmit={handleNewTopicSubmit} className="mb-4">
                <h2 className="text-xl font-semibold mb-2">New Topic</h2>
                <div className="mb-4">
                    <label htmlFor="newTopicTitle" className="block text-gray-700 font-semibold mb-1">
                        Title
                    </label>
                    <input
                        id="newTopicTitle"
                        type="text"
                        value={newTopicTitle}
                        onChange={(event) => setNewTopicTitle(event.target.value)}
                        className="border border-gray-300 rounded-md px-4 py-2 w-full"
                        required
                    />
                </div>
                <div className="mb-4">
                    <label htmlFor="newTopicDescription" className="block text-gray-700 font-semibold mb-1">
                        Description
                    </label>
                    <textarea
                        id="newTopicDescription"
                        value={newTopicDescription}
                        onChange={(event) => setNewTopicDescription(event.target.value)}
                        className="border border-gray-300 rounded-md px-4 py-2 w-full h-20"
                        required
                    />
                </div>
                <button
                    type="submit"
                    className="inline-block bg-blue-500 hover:bg-blue-600 font-semibold px-4 py-2 rounded-md transition-colors duration-300"
                >
                    Create Topic
                </button>
            </form>
            {topics.data.map(topic => (
                <div key={topic.id} className="mb-4">
                    <h2 className="text-xl font-semibold mb-2">{topic.title}</h2>
                    <p className="text-gray-500 mb-2">{topic.description}</p>
                    <p className="text-gray-500">Created At: {topic.created_at}</p>
                    <p className="text-gray-500">Updated At: {topic.updated_at}</p>
                    <Link href={`/topic/${topic.id}`}>Vai al Topic</Link>
                    <button onClick={() => deleteTopic(topic.id)}>Elimina Topic</button>
                </div>
            ))}
        </div>
    );
};

export default TopicsTable;
