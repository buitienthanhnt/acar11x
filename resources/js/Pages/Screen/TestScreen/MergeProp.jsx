
import SingleLayout from '@/Layouts/BuildLayout/SingleLayout';
import { Button } from '@material-tailwind/react';
import { Head, router } from '@inertiajs/react';

const MergeProp = (props) => {
	console.log(props);

	const { notifi, data } = props;
	/**
	 * để sử dụng được tính năng merge props thì router gửi đi cần có tham số only: ['notifi']
	 * https://laracasts.com/discuss/channels/inertia/inertia-deepmerge-laravel-resource-collections-pagination-pagenan
	 * https://www.youtube.com/watch?v=VYd23Pu6Yq0
	 * cái này thường được sử dụng để gộp 1 danh sách trong cùng 1 controller, PageScreen, chỉ thay đổi tham số thư phân trang: $page
	 * hoặc như trong ví dụ là gộp thông báo.
	 */
	const onAddNoti = () => {
		router.post("/test/post-merge", {}, { only: ['notifi', 'chat', 'data', 'pops'] });
	}

	/**
	 * reset list notification by reload page and reset param: ['notifi']
	 */
	const resetNoti = () => {
		router.reload({
			reset: ['notifi']
		})
	}
	return (
		<>
			<Head title='test merge props'></Head>
			<div >
				{notifi && notifi.map((not, index) => {
					return <p key={index}>{not}</p>
				})}
			</div>
			<div className='flex gap-2'>
				<Button onClick={onAddNoti}>add noti and merge props</Button>
				<Button onClick={resetNoti}>reset notifi</Button>
			</div>
		</>
	)
}

MergeProp.layout = page => <SingleLayout>
	{page}
</SingleLayout>

export default MergeProp;