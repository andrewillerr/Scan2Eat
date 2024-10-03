const baseUrl: string = 'http://192.168.108.205/api'; // เปลี่ยน URL ตามที่ใช้งานจริง

// ฟังก์ชันดึงข้อมูลจำนวนโต๊ะที่ว่าง
async function fetchAvailableTables(): Promise<void> {
    try {
        const response = await fetch(`${baseUrl}/get_available_tables.php`);
        if (response.ok) {
            const availableTables: Array<{ table_number: number }> = await response.json();
            document.getElementById('availableTables')!.innerHTML = `${availableTables.length} โต๊ะว่าง`;

            // แสดงรายการโต๊ะที่ว่าง
            const availableTablesList = document.getElementById('availableTablesList')!;
            availableTablesList.innerHTML = ''; // ล้างข้อมูลก่อนหน้า
            availableTables.forEach((table) => {
                const listItem = document.createElement('li');
                listItem.textContent = `โต๊ะที่ ${table.table_number}`;
                availableTablesList.appendChild(listItem);
            });
        } else {
            throw new Error('Failed to load available tables');
        }
    } catch (error) {
        console.error(error);
        document.getElementById('availableTables')!.innerHTML = 'ไม่สามารถโหลดข้อมูลได้';
    }
}

// ฟังก์ชันดึงข้อมูลรายการการสั่งซื้อที่ยังไม่ชำระเงิน
async function fetchUnpaidOrders(): Promise<void> {
    try {
        const response = await fetch(`${baseUrl}/get_unpaid_orders.php`);
        if (response.ok) {
            const unpaidOrders: Array<{
                order_id: number;
                table_number: number;
                items: Array<{ item_name: string }>;
            }> = await response.json();

            const ordersTableBody = document.querySelector('#unpaidOrdersTable tbody')!;
            ordersTableBody.innerHTML = ''; // ล้างข้อมูลก่อนหน้า

            if (unpaidOrders && unpaidOrders.length > 0) {
                unpaidOrders.forEach((order) => {
                    const itemsList = order.items && order.items.length > 0 ? order.items.map((item) => item.item_name).join(', ') : 'ไม่พบข้อมูลรายการอาหาร';
                    const itemCount = order.items ? order.items.length : 0; // จำนวนรายการอาหาร

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>โต๊ะที่ ${order.table_number}</td>
                        <td>${itemsList}</td>
                        <td>${itemCount} รายการ</td> <!-- แสดงจำนวนรายการอาหาร -->
                        <td class="pending">ยังไม่ชำระ</td>
                        <td><button class="pay-button" onclick="markAsPaid(${order.order_id}, this)">ชำระเงิน</button></td>
                    `;
                    ordersTableBody.appendChild(row);
                });
            } else {
                ordersTableBody.innerHTML = '<tr><td colspan="5">ไม่พบการสั่งซื้อที่ยังไม่ชำระเงิน</td></tr>';
            }
        } else {
            throw new Error('Failed to load unpaid orders');
        }
    } catch (error) {
        console.error(error);
        document.querySelector('#unpaidOrdersTable tbody')!.innerHTML = '<tr><td colspan="5">ไม่สามารถโหลดข้อมูลได้</td></tr>';
    }
}

// ฟังก์ชันการอัปเดตสถานะการชำระเงิน
async function markAsPaid(orderId: number, button: HTMLButtonElement): Promise<void> {
    try {
        const response = await fetch(`${baseUrl}/mark_as_paid.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                order_id: orderId,
                status: 'paid',
            }),
        });

        if (response.ok) {
            alert('ชำระเงินสำเร็จ');

            // เปลี่ยนแค่สถานะในตารางและปิดการใช้งานปุ่ม
            const row = button.closest('tr');
            const statusCell = row!.querySelector('td:nth-child(4)')!;
            const payButton = row!.querySelector('button')!;

            statusCell.textContent = 'ชำระแล้ว';
            statusCell.classList.remove('pending');
            statusCell.classList.add('status');

            payButton.disabled = true; // ทำให้ปุ่มไม่สามารถคลิกได้
            payButton.style.backgroundColor = 'gray'; // เปลี่ยนสีปุ่ม
        } else {
            throw new Error('Failed to mark order as paid');
        }
    } catch (error) {
        console.error(error);
        alert('ไม่สามารถอัปเดตสถานะได้');
    }
}

// ฟังก์ชันดึงข้อมูลรายรับทั้งหมดของร้าน
async function fetchTotalRevenue(): Promise<void> {
    try {
        const response = await fetch(`${baseUrl}/get_total_revenue.php`);
        if (response.ok) {
            const revenue: { total: number } = await response.json();
            if (revenue && revenue.total !== undefined) {
                document.getElementById('totalRevenue')!.innerHTML = `${revenue.total.toFixed(2)} บาท`;
            } else {
                document.getElementById('totalRevenue')!.innerHTML = 'ไม่พบข้อมูลรายรับ';
            }
        } else {
            throw new Error('Failed to load total revenue');
        }
    } catch (error) {
        console.error(error);
        document.getElementById('totalRevenue')!.innerHTML = 'ไม่สามารถโหลดข้อมูลได้';
    }
}

// ฟังก์ชันดึงข้อมูลรายรับตามวันที่เลือก
async function fetchRevenueByDate(): Promise<void> {
    const selectedDate = (document.getElementById('revenueDate') as HTMLInputElement).value;
    if (!selectedDate) {
        document.getElementById('totalRevenue')!.innerHTML = 'กรุณาเลือกวันที่';
        return;
    }
    try {
        const response = await fetch(`${baseUrl}/get_total_revenue.php?date=${selectedDate}`);
        if (response.ok) {
            const revenue: { total: number } = await response.json();
            if (revenue && revenue.total !== undefined) {
                document.getElementById('totalRevenue')!.innerHTML = `${revenue.total.toFixed(2)} บาท`;
            } else {
                document.getElementById('totalRevenue')!.innerHTML = 'ไม่พบข้อมูลรายรับในวันที่เลือก';
            }
        } else {
            throw new Error('Failed to load revenue for selected date');
        }
    } catch (error) {
        console.error(error);
        document.getElementById('totalRevenue')!.innerHTML = 'ไม่สามารถโหลดข้อมูลได้';
    }
}

// เรียกใช้ฟังก์ชันเพื่อดึงข้อมูลเมื่อโหลดหน้า
fetchAvailableTables();
fetchUnpaidOrders();
fetchTotalRevenue();
