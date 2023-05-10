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

    useEffect(() => {
        axios.get(`/api/topics/${router.query.id}`)
            .then(response => {
                const { topic, comments } = response.data;
                setTopic(topic);
                setComments(comments.data);
            })
            .catch(error => console.log(error));
    }, [router.query.id]);

    return (
        <AppLayout >
            <div className="mx-auto p-4 bg-white shadow-md">
                {topic && (
                    <div>
                        <h1 className="text-3xl font-bold mb-2">{topic.title}</h1>
                        <p className="text-gray-600">{topic.description}</p>
                        <div className="flex justify-between mt-4 text-sm text-gray-500">
                            <p>Created At: {topic.created_at}</p>
                            <p>Updated At: {topic.updated_at}</p>
                        </div>
                    </div>
                )}
                {comments.length > 0 && (
                    <div className="mt-8">
                        <h2 className="text-2xl font-bold mb-4">Comments</h2>
                        {comments.map(comment => (
                            <div key={comment.id} className="mb-4">
                                <p>{comment.text}</p>
                                <div className="flex justify-between mt-2 text-sm text-gray-500">
                                    <p>Created At: {comment.created_at}</p>
                                    <p>Updated At: {comment.updated_at}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </AppLayout>
    );
};

export default Topic;

