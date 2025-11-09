import { Head, Link, WhenVisible, usePage, usePoll } from "@inertiajs/react";
import RelatedPage from "@/Components/Custom/RelatedPage";
import { ImageType, TextEditorType, TextType, VideoType, TextAreaType, Timeline, CarouselImage } from "@/Components/PageContent";
import { Tags, Info, Propose, CommentForm, CommentList } from "@/Components/PageComponent";
import SingleLayout from "@/Layouts/BuildLayout/SingleLayout";
import { BreadCategory, CenterCategory, PageGrid, SuggetVertical } from "@/Components/Custom";
import usePageRandom from "@/hook/usePageRandom";
import AsyncLoad from "@/Components/AsyncLoad";

export default function Detail({ page: { title, desciption, page_contents, tags, categories, id, writer, source } }) {

    return (
        <SingleLayout>
            <Head title="chi tiết">
            </Head>
            <div className="grid gap-y-1">
                <BreadCategory categories={categories}></BreadCategory>
                <PageInfo title={title} desciption={desciption}></PageInfo>
                <PageWriter writer={writer}></PageWriter>
                <PageContent pageContents={page_contents}></PageContent>
                <Info info={source} pageId={id}></Info>
                <Tags tags={tags}></Tags>
                <CommentList></CommentList>
                <CommentForm pageId={id}></CommentForm>
                <PageLinks></PageLinks>
                <SuggetVertical pageId={id} title={'Tin cùng chuyên mục:'}></SuggetVertical>
                <AsyncLoad key={'centerCategory'} name={'centerCategory'} type={'option'}>
                    <CenterCategory></CenterCategory>
                </AsyncLoad>
                <RelatedPage></RelatedPage>
                <div className="grid grid-cols-4 gap-1">
                    <div className="col-span-4 md:col-span-2">
                        <Propose></Propose>
                    </div>
                    <div className="col-span-4 md:col-span-2 bg-white rounded-md justify-center flex p-1">
                        <span className="font-semibold text-black text-xl">Quảng cáo!</span>
                    </div>
                </div>
            </div>
        </SingleLayout>
    )
}

const PageInfo = ({ title, desciption }) => {
    return (
        <div className="bg-white dark:bg-gray-500 rounded-md p-1">
            <p className="text-xl font-bold text-blue-500">{title}</p>
            <p className="text-md text-gray-800 dark:text-white" dangerouslySetInnerHTML={{ __html: desciption }}></p>
        </div>
    )
}

const PageContent = ({ pageContents }) => {
    if (!pageContents.length) {
        return (<div className="flex bg-white p-2 justify-center items-center">
            <h3 className="text-orange-500 underline font-italic font-semibold">Not content here!</h3>
        </div>);
    }

    return (
        <div className="bg-white  dark:bg-gray-500 rounded-md p-1 lg:px-2 mt-1">
            {pageContents.map(function (content, index) {
                let render;
                switch (content.type) {
                    case 'text':
                        render = <TextType content={content}></TextType>
                        break;
                    case 'textarea':
                        render = <TextAreaType content={content}></TextAreaType>
                        break;
                    case 'textEditor':
                        render = <TextEditorType content={content}></TextEditorType>
                        break;
                    case 'file':
                    case 'imageChoose':
                        render = <ImageType content={content}></ImageType>
                        break;
                    case 'video':
                        render = <VideoType content={content}></VideoType>
                        break;
                    case 'timeline':
                        render = <Timeline content={content}></Timeline>
                        break;
                    case 'carousel':
                        render = <CarouselImage content={content}></CarouselImage>
                        break;
                    default:
                        render = (
                            <div>
                                {content.type}
                            </div>
                        )
                        break;
                }
                return <div key={`content-${index}`}>
                    {render}
                    {index < pageContents.length - 1 ? (<div className="h-[1px] bg-gray-400 my-0.5"></div>) : null}
                </div>;
            })}
        </div>
    );
}

export const PageWriter = ({ writer }) => {
    if (!writer) {
        return null;
    }

    return (
        <div className="bg-white rounded-md p-2 flex space-x-4">
            <img src={writer.image_path} alt={writer.name} className="w-[60px] h-[60px] rounded-full object-center" />
            <Link className="items-center flex" href={route('writerDetail', { id: writer.id })}>
                <div>
                    <p className="font-semibold text-md">{writer.name}</p>
                    <p className="font-semibold text-md text-blue-gray-700">{writer.email}</p>
                </div>
            </Link>
        </div>
    )
}

const PageLinks = () => {
    const { pages, isLoading } = usePageRandom();

    return (
        <WhenVisible data={'pages'} fallback={() => <div>Loading...</div>}>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-1 rounded-md">
                <div className="col-span-1 lg:col-span-4 bg-white rounded-md">
                    <p className="font-bold text-xl bg-white p-2 rounded-md">Danh sách gợi ý!</p>
                    <PageGrid data={pages}></PageGrid>
                </div>
                <div className="invisible md:visible lg:col-span-1 rounded-md justify-center flex bg-white">
                    <span className="font-extrabold">Marketing banner!</span>
                </div>
            </div>
        </WhenVisible>
    )
}

const FetchOnlineData = () => {
    const { props: { poll } } = usePage();
    // usePoll(2000, {
    //     onStart() {
    //         console.log('Polling request started')
    //     },
    //     onFinish() {
    //         console.log('Polling request finished')
    //     }
    // })

    /**
     * https://inertiajs.com/polling
     * usePoll: tính năng tự động lấy data mới của requeat hiện tại theo khoảng thời gian:
     * cái này khá hay trong việc xây dựng tính năng chat trực tuyến.
     * Qua đó, khi data trên server thay đổi thì nó sẽ được cập nhập mới theo luôn.
     */
    const { start, stop } = usePoll(4000, {}, {
        autoStart: false,
    })

    return (
        <div className="flex gap-2 bg-white p-2">
            <p>poll data: {poll}</p>
            <button onClick={start} className="btn rounded-md bg-blue-gray-300 p-2">Start polling</button>
            <button onClick={stop} className="btn rounded-md bg-red-300 p-2">Stop polling</button>
        </div>
    )
}
