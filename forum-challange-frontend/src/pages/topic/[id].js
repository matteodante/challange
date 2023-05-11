
import Head from 'next/head'
import Link from 'next/link'
import { useAuth } from '@/hooks/auth'
import { useState, useEffect } from 'react'
import axios from '@/lib/axios'
import { useRouter } from 'next/router'
import GuestLayout from '@/components/Layouts/GuestLayout'
import AppLayout from '@/components/Layouts/AppLayout'

const Topic = () => {
    const router = useRouter()
    const [topic, setTopic] = useState(null);
    const [comments, setComments] = useState([]);
    const [commentCreated, setCommentCreated] = useState(false);
    const [editCommentId, setEditCommentId] = useState(null);
    const [editCommentText, setEditCommentText] = useState('');

    const [editTopicId, setEditTopicId] = useState(null);
    const [editTopicTitle, setEditTopicTitle] = useState('');
    const [editTopicDescription, setEditTopicDescription] = useState('');

    useEffect(() => {

        if (Object.keys(router.query).length === 0) return
        axios.get(`/api/topics/${router.query.id}`)
            .then(response => {
                const { topic, comments } = response.data;
                setTopic(topic);
                setComments(comments.data);
            })
            .catch(error => console.log(error));
    }, [router.query.id, commentCreated]);

    const [newCommentText, setNewCommentText] = useState('');

    const deleteComment = (id) => {
        axios.delete(`/api/topics/${router.query.id}/comments/` + id)
            .then(response => {
                console.log(response.data);
                setCommentCreated(!commentCreated);
            })
            .catch(error => console.log(error));
    }

    const handleNewCommentSubmit = (event) => {
        event.preventDefault();

        const newTopic = {
            text: newCommentText,
        };

        axios.post(`/api/topics/${router.query.id}/comments`, newTopic)
            .then(response => {
                console.log(response.data);
                setNewCommentText('');
                setCommentCreated(!commentCreated);
            })
            .catch(error => console.log(error));
    };


    return (
        <>
            <div className="max-w-xl mx-auto p-4 bg-white shadow-md">
                {topic && (
                    <div>
                        {editTopicId === topic.id ? (
                            <div className="mb-4">
                                <input
                                    type="text"
                                    value={editTopicTitle}
                                    onChange={(event) => setEditTopicTitle(event.target.value)}
                                    className="border border-gray-300 rounded-md px-4 py-2 w-full"
                                    required
                                />
                                <input
                                    type="text"
                                    value={editTopicDescription}
                                    onChange={(event) => setEditTopicDescription(event.target.value)}
                                    className="border border-gray-300 rounded-md px-4 py-2 w-full"
                                    required
                                />
                                <button onClick={() => {
                                    axios.put(`/api/topics/${router.query.id}`, { title: editTopicTitle, description: editTopicDescription })
                                        .then(response => {
                                            console.log(response.data);
                                            setEditTopicId(null);
                                            setCommentCreated(!commentCreated);
                                        })
                                        .catch(error => console.log(error));
                                }}>Salva</button>
                                <button onClick={() => {
                                    setEditTopicId(null);
                                    setEditTopicTitle('');
                                    setEditTopicDescription('');
                                }}>Cancella</button>
                            </div>
                        ) : (
                            <>
                                <h1 className="text-3xl font-bold mb-2">{topic.title}</h1>
                                <p className="text-gray-600">{topic.description}</p>
                            </>
                        )}
                        <div className="flex justify-between mt-4 text-sm text-gray-500">
                            <p>Created At: {topic.created_at}</p>
                            <p>Updated At: {topic.updated_at}</p>
                        </div>
                        <button onClick={() => {
                            setEditTopicId(topic.id);
                            setEditTopicTitle(topic.title);
                            setEditTopicDescription(topic.description);
                        }}>Modifica Topic</button>
                    </div>
                )}



                {comments.length > 0 && (
                    <div className="mt-8">
                        <h2 className="text-2xl font-bold mb-4">Comments</h2>
                        {comments.map(comment => (
                            <div key={comment.id} className="mb-4">
                                {editCommentId === comment.id ? (
                                    <div className="mb-4">
                                        <input
                                            type="text"
                                            value={editCommentText}
                                            onChange={(event) => setEditCommentText(event.target.value)}
                                            className="border border-gray-300 rounded-md px-4 py-2 w-full"
                                            required
                                        />
                                        <button onClick={() => {
                                            axios.put(`/api/topics/${router.query.id}/comments/${comment.id}`, { text: editCommentText })
                                                .then(response => {
                                                    console.log(response.data);
                                                    setEditCommentId(null);
                                                    setCommentCreated(!commentCreated);
                                                })
                                                .catch(error => console.log(error));
                                        }}>Save</button>
                                        <button onClick={() => {
                                            setEditCommentId(null);
                                            setEditCommentText('');
                                        }}>Cancel</button>
                                    </div>
                                ) : (
                                    <p className="text-gray-500 mb-2">{comment.text}</p>
                                )}
                                <div className="flex justify-between mt-2 text-sm text-gray-500">
                                    <p>Created At: {comment.created_at}</p>
                                    <p>Updated At: {comment.updated_at}</p>
                                    <button onClick={() => deleteComment(comment.id)}>Elimina commento</button>
                                    <button onClick={() => {
                                        setEditCommentId(comment.id);
                                        setEditCommentText(comment.text);
                                    }}>Modifica commento</button>
                                </div>
                            </div>
                        ))}
                    </div>
                )}

                <form onSubmit={handleNewCommentSubmit} className="mb-4 mt-4">
                    <h2 className="text-xl font-semibold mb-2">New Comment</h2>
                    <div className="mb-4">
                        <label htmlFor="newCommentTitle" className="block text-gray-700 font-semibold mb-1">
                            Title
                        </label>
                        <input
                            id="newCommentTitle"
                            type="text"
                            value={newCommentText}
                            onChange={(event) => setNewCommentText(event.target.value)}
                            className="border border-gray-300 rounded-md px-4 py-2 w-full"
                            required
                        />
                    </div>
                    <button
                        type="submit"
                        className="inline-block bg-blue-500 hover:bg-blue-600 font-semibold px-4 py-2 rounded-md transition-colors duration-300"
                    >
                        Create Comment
                    </button>
                </form>
            </div>
        </>
    );
};

export default Topic;

