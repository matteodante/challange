import AppLayout from '@/components/Layouts/AppLayout'
import Head from 'next/head'
import React, { useState, useEffect } from 'react';
import Topics from './Topics';

const Dashboard = () => {



    return (
        <AppLayout
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard
                </h2>
            }>
            <Head>
                <title>Laravel - Dashboard</title>
            </Head>

        </AppLayout>
    )
}

export default Dashboard
