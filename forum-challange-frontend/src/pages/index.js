import Head from 'next/head'
import Link from 'next/link'
import { useAuth } from '@/hooks/auth'
import { useState, useEffect } from 'react'
import axios from '@/lib/axios'
import TopicsTable from '@/components/Customs/TopicsTable'

export default function Home() {
    const { user, logout } = useAuth({ middleware: 'guest' })
    const [topics, setTopics] = useState(null);
    const [nextPageUrl, setNextPageUrl] = useState(null);

    useEffect(() => {
        fetchTopics('/api/topics');
    }, []);

    const fetchTopics = (url) => {
        axios
            .get(url)
            .then(response => response.data)
            .then(data => {
                setTopics(data.topics);
                setNextPageUrl(data.topics.next_page_url);
            })
            .catch(error => console.log(error));
    };

    const loadMoreTopics = () => {
        if (nextPageUrl) {
            fetchTopics(nextPageUrl);
        }
    };

    return (
        <>
            <Head>
                <title>Laravel</title>
            </Head>

            <div className="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                {user ? (
                    <>
                        <Link
                            href="/"
                            className="ml-4 text-sm text-gray-700 underline">
                            Dashboard
                        </Link>
                        <Link
                            href="/"
                            as="button"
                            className="ml-4 text-sm text-gray-700 underline"
                            onClick={logout}>
                            Logout
                        </Link>
                    </>

                ) : (
                    <>
                        <Link
                            href="/login"
                            className="text-sm text-gray-700 underline">
                            Login
                        </Link>

                        <Link
                            href="/register"
                            className="ml-4 text-sm text-gray-700 underline">
                            Register
                        </Link>
                    </>
                )}
            </div>

            <div className="p-6 bg-white border-b border-gray-200">
                {topics ? <TopicsTable topics={topics} /> : <p>Loading...</p>}
            </div>

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            {nextPageUrl && <button onClick={loadMoreTopics}>Load More</button>}
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}
