import React from 'react';
import TopBar from './TopBar';
import { Head } from '@inertiajs/react';

const BaseLayout: React.FC<{
    children: React.ReactNode,
    style?: React.CSSProperties,
    title?: string,
}> = ({ children, style, title }) => {

    return (
        <div style={{ ...styles.page, ...style }}>
            {title && <Head title={title}></Head>}
            <TopBar></TopBar>
            {children}
        </div>
    )
}

export default BaseLayout;

const styles: {
    [key: string]: React.CSSProperties
} = {
    page: {
        display: 'flex',
        flexDirection: 'column',
        minHeight: '100vh',
        width: '100%',
        maxWidth: '100%',
        padding: 10,
        // backgroundColor: '#E4E4E4',
    },
    section: {
        margin: 10,
        padding: 10,
        flexGrow: 1,
    },
};
