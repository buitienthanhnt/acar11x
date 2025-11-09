import AsyncLoad from '@/Components/AsyncLoad';
import GoldChart from '@/Components/ChartComponent/GoldChart';
import VietLotChart from '@/Components/ChartComponent/VietLotChart';
import { CenterCategory, DupVideos, HomeDemo, HomeTime, ImagePage, PageGrid, PageInfo, RandomHorizon, SuggetVertical, TimeList, TopComment } from '@/Components/Custom';
import Banner from '@/Components/Custom/Banner';
import { TopPage } from '@/Components/PageComponent';
import { ListSke } from '@/Components/Skeleton';
import SingleLayout from '@/Layouts/BuildLayout/SingleLayout';
import { Link, Head, } from '@inertiajs/react';
import React from "react";
import Slider from "react-slick";

export default function Welcome({ auth, components }) {

    return (
        <SingleLayout>
            <>
                <Head title="trang chủ">
                    <meta name="author" content="thanhnt for developer" />
                    <meta name='group' content='develop web react laravel'></meta>
                    <link href='' rel='stylesheet'></link>
                    <script></script>
                </Head>
                <div className="sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"> {/* relative sm:flex  */}
                    <div className="space-y-2 md:p-6 lg:p-8"> {/* max-w-7xl mx-auto  p-6 lg:p-8  */}
                        <HomeTime></HomeTime>
                        {components.map(({ value, name }, index) => {
                            switch (value.type) {
                                case 'banner':
                                    return <Banner key={index} data={value} name={name}></Banner>
                                case 'topPage':
                                    return <AsyncLoad key={index} name={name} type={'option'}>
                                        <TopPage></TopPage>
                                    </AsyncLoad>
                                case 'videos':
                                    return <AsyncLoad key={index} name={name} type={'option'}>
                                        <DupVideos></DupVideos>
                                    </AsyncLoad>
                                case 'topComment':
                                    return <AsyncLoad key={index} name={name} type={'option'}>
                                        <TopComment></TopComment>
                                    </AsyncLoad>
                                case 'timeLine':
                                    return <AsyncLoad key={index} name={name} type={'option'} buffer={1000} always>
                                        <TimeList key={index} name={name}></TimeList>
                                    </AsyncLoad>
                                case 'centerCategory':
                                    return <AsyncLoad key={index} name={name} type={'option'}>
                                        <CenterCategory key={index} name={name}></CenterCategory>
                                    </AsyncLoad>
                                case 'latestPage':
                                    return <AsyncLoad key={index} name={name} type={'option'}>
                                        <SuggetVertical title={'Giới thiệu'}></SuggetVertical>
                                    </AsyncLoad>
                                case 'gridPage':
                                    return <AsyncLoad key={index} name={name} type={'option'}>
                                        <PageGrid></PageGrid>
                                    </AsyncLoad>
                                case 'pageRandom':
                                    return <AsyncLoad key={index} name={name} type={'option'}>
                                        <HomeDemo></HomeDemo>
                                    </AsyncLoad>
                                case 'suggest':
                                    return <AsyncLoad key={index} name={name} type={'option'}>
                                        <RandomHorizon></RandomHorizon>
                                    </AsyncLoad>
                                case 'listHorizon': // listHorizon SuggetVertical
                                    return <AsyncLoad key={index} name={name} type={'defer'}>
                                        <SwipeToSlide title={'Gợi ý'}></SwipeToSlide>
                                    </AsyncLoad>
                                case 'chart':
                                    return <AsyncLoad key={index} name={name}>
                                        <GoldChart></GoldChart>
                                    </AsyncLoad>
                                case 'listVertical':
                                    return <AsyncLoad key={index} name={name} type={'option'}>
                                        <SuggetVertical title={'Ngẫu nhiên'}></SuggetVertical>
                                    </AsyncLoad>
                                default:
                                    return;
                                    break;
                            }
                        })}
                        <PageInfo></PageInfo>
                    </div>
                </div>
                <HomeStyle></HomeStyle>
            </>
        </SingleLayout>
    );
}

const HomeStyle = () => {
    return (
        <style>{`
            html {scroll-behavior: smooth;}
            .bg-dots-darker {
                    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
            }
            @media (prefers-color-scheme: dark) {
                .dark\\:bg-dots-lighter {
                    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
                }
            }
            .player-wrapper {
                position: relative;
                padding-top: 177.76%; /* 1095 / 616 = 1.7776 */
            }
            .react-player {
                position: absolute;
                top: 0;
                left: 0;
            }
        `}</style>
    )
}

function SwipeToSlide({ data, title }) {
    // const { props: { swipeList } } = usePage();

    // https://taynamsolution.vn/chuyen-muc/tin-tuc/page/2/
    // padding between slider item
    /* the slides */
    // .slick-slide {margin: 0 27px;}
    /* the parent */
    // .slick-list {margin: 0 -27px;}
    const settings = {
        centerMode: window.innerWidth < 720 ? true : false,
        centerPadding: "60px",
        infinite: true,
        dots: true,
        slidesToShow: window.innerWidth < 720 ? 1 : 3,
        swipeToSlide: true,
        arrows: false,
        afterChange: function (index) {
            // console.log(
            //     `Slider Changed to: ${index + 1}, background: #222; color: #bada55`
            // );
        }
    };

    return (
        <>
            <style>
                {".slick-slide > div { margin: 0 8px;}"}
            </style>
            <div className="slider-container bg-white p-2 rounded-lg space-y-2 shadow-xl pb-8">
                <p className='font-semibold text-2xl text-blue-700 flex bg-white'>
                    {title || "Danh sách ngẫu nhiên"}:
                </p>
                <Slider {...settings}>
                    {data.map((item, index) => <SliderItem key={`sli-${index}`} item={item}></SliderItem>)}
                </Slider>
            </div>
        </>

    )

    return (
        <Deferred data="swipeList" fallback={() => <ListSke></ListSke>}>
            <style>
                {".slick-slide > div { margin: 0 8px;}"}
            </style>
            {swipeList && <div className="slider-container bg-white p-2 md:pb-8 rounded-lg space-y-2 shadow-xl">
                <p className='font-semibold text-2xl text-blue-700 flex bg-white'>
                    Danh sách ngẫu nhiên:
                </p>
                <Slider {...settings}>
                    {swipeList.map((item, index) => <SliderItem key={`sli-${index}`} item={item}></SliderItem>)}
                </Slider>
            </div>}
        </Deferred>
    );
}

function SliderItem({ item }) {
    return (
        <Link href={route('detail', { alias: item.alias })} prefetch className='bg-green-300 h-48 md:h-72 flex rounded-md justify-center items-center relative'>
            <ImagePage source={item.image_path} className={'w-full h-full rounded-md'}></ImagePage>
            <div className='absolute bg-[#c2dbeb99] left-0 bottom-0 px-2 pl-1 py-1 rounded-sm min-h-[56px]'>
                <p className='font-semibold text-md md:text-xl line-clamp-2 hover:text-white'>
                    {item.title}
                </p>
            </div>
        </Link>
    )
}
