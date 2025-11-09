import { HandThumbUpIcon, HeartIcon, LinkIcon, ChatBubbleOvalLeftEllipsisIcon, FireIcon } from "@heroicons/react/24/solid";
import { useCallback, useMemo, useState } from "react";
import { Rating } from "@material-tailwind/react";
import { useForm } from "@inertiajs/react";
import Urls from "@/network/Urls";
// import { router } from '@inertiajs/react'
// import axios from "axios";
// import rApi from "@/network/rApi";

const typeInfo = ['like', 'heart', 'fire', 'link',];

/**
 * Sử dụng: localStorage để lưu trữ các bài viết đã thích.
 */
export default function Info({ pageId, info, comments_count, className }) {
	const [action, setAction] = useState('');
	const [savelink, setSavelink] = useState(false);

	const { errors, post } = useForm('')
	const checkedInfo = useMemo(() => {
		let saved = {};
		typeInfo.map((t) => {
			/**
			 * lấy thông tin đã lưu trữ trong bộ nhớ cục bộ
			 */
			const data = localStorage.getItem(`page-info-${t}`);
			saved[t] = data ? data.split('|') : [];
		})
		return saved;
	}, [action]);

	/**
	 * kiểm tra xem đã thêm vào bộ nhớ cục bộ hay chưa.
	 */
	const checkSelected = useCallback((type) => {
		return checkedInfo[type].includes(pageId.toString());
	}, [pageId, checkedInfo]);

	const onPressItem = useCallback(async (type) => {
		const key = `page-info-${type}`;
		const checked = localStorage.getItem(key);
		const listChecked = checked ? checked.split('|') : [];
		const isChecked = listChecked.includes(pageId.toString());

		if (type === 'link') {
			// await navigator.clipboard.writeText(pageId);
			setSavelink(true);
			return;
		}
		/**
		 * controller auto detech user context of the request. 
		 */
		axios.post(Urls.addSource, {
			target_id: pageId,
			action: !isChecked ? 'add' : 'sub',
			action_type: type,
			type: 'page',
		}).then(function ({ data }) {
			let newListChecked = isChecked ? listChecked.filter(function (i) {
				return i !== pageId.toString();
			}) : [pageId, ...listChecked];
			/**
			 * gán giá trị vào bộ nhớ cục bộ trình duyệt.
			 */
			localStorage.setItem(key, newListChecked.join('|'));
			setAction(newListChecked.join('|'));
		}).catch(function (error) {
			console.log('request error: ', error);
		})
	}, [post]);

	// const onSelectType = useCallback( async ()=>{
	// sẽ không dùng được các phương thức của inertia để gọi yêu cầu tĩnh vì nó luôn luôn cần trả về 1 Inertia thành phần
	// router.get('/test/json', {}, {
	// 	preserveState: true,
	// 	onSuccess: (params)=>{
	// 		console.log('===>', params);
	// 	}
	// })
	// cho nên khi cần gọi yêu cầu tĩnh thì ta phải dùng fetch hoặc axios.
	// 1. fetch:
	// const data = await fetch('/test/json');
	// const val = await data.json();
	// 2. axios:
	// const data = await axios.get('/test/json');
	// console.log('====================================');
	// console.log(data.data);
	// 3. use custom axios network api:
	// let data;
	// try {
	// 	data = await rApi.callRequest({
	// 		url: '/test/json',
	// 		method: 'GET',
	// 	}); // json response data by server. 
	// } catch (error) {
	// 	data = error.data.message; // string
	// }
	// console.log('====================================');
	// console.log(data);
	// }, [])

	return (
		<div className={`bg-white rounded-md p-4 ${className}`}>
			{savelink && <div role="alert" class="mb-4 relative flex w-full p-3 text-sm text-white bg-green-600 rounded-md">
				saved for page link.
				<button onClick={() => setSavelink(false)} class="flex items-center justify-center transition-all w-8 h-8 rounded-md text-white hover:bg-white/10 active:bg-white/10 absolute top-1.5 right-1.5" type="button">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
				</button>
			</div>}
			<div className="grid md:grid-cols-2">
				<div className="col-span-1">
					<Rating value={4} readonly />
				</div>
				<div className="justify-end flex gap-3 col-span-1">
					{typeInfo.map(function (type, index) {
						return (
							<div className="bg-orange-200 p-1 px-2 rounded-full flex space-x-2 hover:scale-125 hover:bg-cyan-200"
								onClick={() => { onPressItem(type) }} key={`info-${index}`}
								style={{ backgroundColor: checkSelected(type) ? 'rgb(149, 210, 250)' : '' }}>
								{info?.value?.[type] && <span className="font-extrabold">{info?.value?.[type]}</span>}
								{(() => {
									switch (type) {
										case 'like':
											return <HandThumbUpIcon className="h-6 w-6" color="red"></HandThumbUpIcon>
										case 'heart':
											return <HeartIcon className="h-6 w-6" color="red"></HeartIcon>;
										case 'link':
											return <LinkIcon className="h-6 w-6" color="green"></LinkIcon>;
										case 'fire':
											return <FireIcon className="h-6 w-6" color="red"></FireIcon>
										default:
											return null;
									}
								})()}
							</div>
						);
					})}
					{
						!!comments_count && <div className='flex space-x-1 bg-orange-200 p-1 px-2 rounded-full hover:scale-125 hover:bg-cyan-200'>
							<span className='font-extrabold'>{comments_count}</span>
							<ChatBubbleOvalLeftEllipsisIcon color='gray' className="h-6 w-6"></ChatBubbleOvalLeftEllipsisIcon>
						</div>
					}
				</div>
			</div>
		</div>)
}