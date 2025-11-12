import SingleLayout from '@/Layouts/BuildLayout/SingleLayout';
import { Head, Link } from '@inertiajs/react';
import { Typography } from '@material-tailwind/react';

const PartialProp = (props) => {
	const {page, demo, user} = props;
	// console.log('====================================');
	// console.log(props);
	
	return (
		<>
		<Head title='test partial reload'></Head>
		<div className='bg-white p-2 flex flex-col gap-2'>
			<p>demo for partial reload props: {JSON.stringify(demo)}</p>
			<p>{JSON.stringify(user)}</p>
			<div className='flex flex-col gap-2'>
				{page.map(item => <p key={item.id} className='font-semibold text-blue-gray-500 underline line-clamp-2'>{item.title}</p>)}
			</div>
			<Link href={window.location.href} data={{page: 2, reload: 1}} only={['demo']} 
				className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded inline-block">
				to page 2 only demo
			</Link>

			<Link href={window.location.href} data={{page: 2.5, reload: 1}} except={['demo']} 
				className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded inline-block">
				to page 2.5 except demo
			</Link>

			<Link href={window.location.href} data={{page: 3, reload: 1}} only={['page']} 
				className="bg-purple-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded inline-block">
				to page 3 only  page
			</Link>

			<Link href={window.location.href} data={{page: 4, reload: 1}} except={['page']}
				className="bg-gray-600 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded inline-block">
				to page 4 except: page
			</Link>

			<Link href={window.location.href} data={{page: 5, reload: 1}}  
				className="bg-green-300 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded inline-block">
				to page 5 not option(default redirect)
			</Link>

			<Link href={window.location.href} data={{page: 6, reload: 1}}  except={['user']}
				className="bg-red-300 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded inline-block">
				to page 6 not option(except user)
			</Link>

			<Typography className='text-gray-500'>
				Partial reload: Tải lại một phần
				href='' data='' only='' except=''. Qua đó <br></br>
					dùng only=[...] thì yêu cầu sẽ chỉ tải lại(làm mới) giá trị đó. Các giá trị khác giữ nguyên<br></br>
					dùng except=[...] thì nó sẽ tải lại(làm mới) tất cả các giá trị ngoài danh sách đó. Các giá trị except giữ nguyên <br></br>
					Nếu không có only hoặc except thì hoạt động bình thường không giữ lại giá trị gì(tải lại hoàn toàn)<br></br>
					Tóm tắt: only: là tính lại(chỉ tải lại giá trị đó, cái khác giữ nguyên), except: là giữ nguyên(không tải lại, chỉ tải lại ngoài except).
			</Typography>
		</div></>
	)
}

PartialProp.layout = page => <SingleLayout children={page}></SingleLayout>

export default PartialProp;