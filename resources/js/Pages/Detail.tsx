import SingleLayout from "@/Layouts/BuildLayout/SingleLayout";
import { Head, Link, router } from '@inertiajs/react';
import { Button } from "@material-tailwind/react";
import { useState } from "react";
import YouTube from "react-youtube";

const Detail = ({ value, once_link }) => {
    const [url, setUrl] = useState('');

    const opts = {
        height: window.innerHeight,
        width: window.innerWidth,
        // height: 350,
        // width: 230,
        playerVars: {
            // https://developers.google.com/youtube/player_parameters
            autoplay: 1,
        },
    };

    const onPlayerReady = (event) => {
        // access to player in all event handlers via event.target
        console.log('????????????', event.target.getSize());
        event.target.pauseVideo();
    }

    return (
        <SingleLayout>
            <Head>
                <title>detail page</title>
            </Head>
            <div>
                <YouTube videoId="Dgq7LdZgYQY" opts={opts} onReady={onPlayerReady} />

                <Link href="home" className="text-xl hover:text-orange-500 font-medium hover:underline"
                    data={{ id: 12, name: 'tahmahand' }}>
                    go to home page layout {value}
                </Link>

                <Link href={''} className="underline hover:text-blue-gray-700 block"> demo to generate url</Link>

                <Link href={once_link} className="hover:text-red-400 hover:underline text-xl my-2 inline-block">once time link</Link>

                <div className="space-x-1">
                    <Button onClick={() => {
                        // setUrl(route('login'));
                        console.log(route().has('login'));
                        // console.log(router.get('home'));
                    }}>
                        view url
                    </Button>

                    <Link href={route("list", { id: 223 })}>
                        <Button>go to List page</Button>
                    </Link>
                </div>
            </div>
        </SingleLayout>
    )
}

export default Detail;
