import { RoomItem as RoomItemType } from '../types/Room';
import { router, } from '@inertiajs/react';
import { CustomTimeTable } from '../Components/CustomCalenda';
import { Button, } from '@material-tailwind/react';
import { XMarkIcon, CubeIcon, CurrencyDollarIcon, UsersIcon } from '@heroicons/react/24/solid';
import { usePageMessage, useRoomOrders, useMode } from '../hooks';
import { listDateToArrayString } from '../Helper/DateTimeHelper';

const RoomItem = ({ room, dateSelected }: { room: RoomItemType, dateSelected?: Date[] }) => {
  const booked = useRoomOrders();
  const messages = usePageMessage();

  const onClickRoom = (room: RoomItemType) => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('room') === room.id.toString()) {
      router.visit(window.location.pathname, {
        method: 'get',
        preserveScroll: true,
      });
    } else {
      router.visit(window.location.href, {
        method: 'get',
        preserveScroll: true,
        data: { room: room.id, selectedDates: dateSelected ? listDateToArrayString(dateSelected) : undefined },
        only: messages ? [] : ['roomSelected', 'selectedDates'], // clear app props if has flash mesasge.
        // preserveState: true, // for remenber old state of page(can save for: dateSelected)
      })
    }
  }
 
  return (
    <div className={`hover:shadow-md rounded-md gap-x-2 flex 
			 h-full ${booked?.id === room.id ? 'bg-gradient-to-r from-green-400 to-green-300' : 'bg-white shadow-sm'}`} onClick={() => {
        onClickRoom(room);
      }}>
      <div>
        <img src={room.image_path} className="w-36 min-w-32 aspect-square rounded-md " alt="" />
      </div>
      <div className='flex flex-col gap-y-1'>
        <p className='font-semibold text-purple-500'>Phòng: {room.title}</p>
        <div className='flex items-center gap-x-1'>
          <CubeIcon className='size-5 text-gray-800'></CubeIcon>
          <p className='text-black font-semibold text-sm md:text-md'>{room.description}</p>
        </div>
        <div className='flex items-center gap-x-1'>
          <CurrencyDollarIcon className='size-5 text-gray-800'></CurrencyDollarIcon>
          <p className='text-black font-semibold text-sm md:text-md'>{room.price} 000 VND</p>
        </div>
        <div className='flex items-center gap-x-1'>
          <UsersIcon className='size-5 text-black'></UsersIcon>
          <p className='font-semibold text-sm md:text-md'>{room.type}</p>
        </div>
      </div>
    </div>
  )
}

type RoomTimeProps = {
  allDisable?: string[],
  dateSelected?: Date[],
  setDateSelected: (dates: Date[]) => void,
  onCheckout: () => void,
}
const RoomTime = ({ allDisable, dateSelected, setDateSelected, onCheckout }: RoomTimeProps) => {
  const booked = useRoomOrders();
  const bookedDate = booked?.booked_dates || [];

  // const onSubmitOrder = useCallback(() => {
  //   const selectedValues = dateSelected.map(d => [
  //     d.getFullYear(),
  //     d.getMonth() + 1 >= 10 ? d.getMonth() + 1 : '0' + (d.getMonth() + 1).toString(),
  //     d.getDate() < 10 ? '0' + d.getDate().toString() : d.getDate(),
  //   ].join('-'));

  //   router.visit(window.location.href, {
  //     method: 'post',
  //     data: {
  //       values: selectedValues,
  //     },
  //   })

  // }, [dateSelected])

  return (
    <div className='space-y-2 grid grid-col-1 md:grid-cols-3 gap-1 lg:gap-2'>
      <div className='space-y-1 md:col-span-2'>
        <CustomTimeTable
          selected={dateSelected}
          onChange={setDateSelected}
          minDate={new Date()}
          // maxDate={new Date(2025, 11, 19)}
          disable={[...bookedDate, ...(allDisable || [])].map(s => new Date(s))}
          forcus={dateSelected?.length ? dateSelected[0] : undefined}
        ></CustomTimeTable>
      </div>
      <div>
        <span className='text-md font-semibold'>Phòng đang chọn: {booked?.title || 'Ngẫu nhiên'}</span>
        <div className='col-span-1 flex flex-col space-y-1'>
          {!!dateSelected?.length && <div className='flex justify-between items-center my-2'>
            <span className='text-md font-semibold'>Thời gian lưu trú:</span>
            <span className='bg-orange-400 rounded-full p-1' onClick={() => { setDateSelected([]); }}>
              <XMarkIcon className='size-4 text-white font-extrabold'></XMarkIcon>
            </span>
          </div>}
          <SelectedInfo dateSelected={dateSelected || []}></SelectedInfo>
          {/* @ts-ignore */}
          {!!dateSelected?.length && <Button placeholder={'view selected'} style={{ marginTop: '10px' }} onClick={onCheckout}>
            <span>Đặt phòng</span>
          </Button>}
        </div>
      </div>
    </div>
  )
}

export const SelectedInfo = ({ dateSelected }: { dateSelected: Date[] }) => {
  const { isDateRangeMode } = useMode();

  if (dateSelected.length === 0) {
    return;
  }

  if (isDateRangeMode) {
    if (dateSelected.length === 1) {
      return <div className='flex flex-col gap-1 md:gap-2'>
        {dateSelected[0] &&
          <div className='text-xl font-semibold text-blue-500'>
            Trong ngày: {dateSelected[0].getFullYear()}-{dateSelected[0].getMonth() + 1}-{dateSelected[0].getDate()}
          </div>
        }
      </div>
    }
    return (
      <div className='flex flex-col gap-1 md:gap-2'>
        {dateSelected[0] &&
          <div className='text-xl font-semibold text-blue-500'>
            Từ ngày: {dateSelected[0].getFullYear()}-{dateSelected[0].getMonth() + 1}-{dateSelected[0].getDate()}
          </div>
        }
        {dateSelected[dateSelected.length - 1] &&
          <div className='text-xl font-semibold text-blue-500'>
            Đến ngày: {dateSelected[dateSelected.length - 1].getFullYear()}-{dateSelected[dateSelected.length - 1].getMonth() + 1}-{dateSelected[dateSelected.length - 1].getDate()}
          </div>
        }
      </div>
    );
  }

  return (
    <div className='grid grid-cols-2 gap-1 md:gap-2'>
      {dateSelected.map((date, index) => {
        return (
          <div key={index} className='p-1 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-md flex justify-center items-center content-center'>
            <span className='text-black font-semibold'>{date.getFullYear()}-{date.getMonth() + 1}-{date.getDate()}</span>
          </div>
        )
      })}
    </div>
  );
}

export { RoomItem, RoomTime };