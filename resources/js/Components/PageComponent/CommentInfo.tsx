
import { useCommentInfo } from '@/hook/useComments';
import { FireIcon, HandThumbUpIcon, HeartIcon, StarIcon } from '@heroicons/react/24/solid';

const CommentInfo = ({ comment }) => {
	const { like, heart, star, fire, onAdd } = useCommentInfo(comment);

	return (
		<div className="flex space-x-3">
			<div className='flex space-x-1'>
				{comment?.source?.value?.['like'] && <span>{comment?.source?.value?.['like']}</span>}
				<HandThumbUpIcon color={like.includes(comment.id.toString()) ? '#27add6' : 'gray'} className="h-5 w-5" onClick={() => onAdd('like')}></HandThumbUpIcon>
			</div>
			<div className='flex space-x-1'>
				{comment?.source?.value?.['heart'] && <span>{comment?.source?.value?.['heart']}</span>}
				<HeartIcon color={heart.includes(comment.id.toString()) ? 'red' : 'gray'} className="h-5 w-5" onClick={() => onAdd('heart')}></HeartIcon>
			</div>
			<div className='flex space-x-1'>
				{comment?.source?.value?.['star'] && <span>{comment?.source?.value?.['star']}</span>}
				<StarIcon color={star.includes(comment.id.toString()) ? 'orange' : 'gray'} className="h-5 w-5" onClick={() => onAdd('star')}></StarIcon>
			</div>
			<div className='flex space-x-1'>
				{comment?.source?.value?.['fire'] && <span>{comment?.source?.value?.['fire']}</span>}
				<FireIcon color={fire.includes(comment.id.toString()) ? 'red' : 'gray'} className="h-5 w-5" onClick={() => onAdd('fire')}></FireIcon>
			</div>
		</div>
	)
}

export default CommentInfo;