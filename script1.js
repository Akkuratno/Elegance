document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('booking-form');
    const serviceSelect = document.getElementById('service');
    const masterSelect = document.getElementById('master');
    const priceDisplay = document.getElementById('price');
    const timeSelect = document.getElementById('time');
    const dateInput = document.getElementById('date');

    const servicePrices = {
        'haircut': 2000,
        'coloring': 3500,
        'manicure': 1500
    };

    // Обработчик изменения услуги
    serviceSelect.addEventListener('change', function() {
        const selectedService = this.value;
        updatePrice(selectedService);
        updateMasters(selectedService);
    });

    // Функция обновления цены
    function updatePrice(service) {
        if (service && servicePrices[service]) {
            priceDisplay.textContent = `${servicePrices[service]} ₽`;
            priceDisplay.style.display = 'block';
        } else {
            priceDisplay.textContent = '';
            priceDisplay.style.display = 'none';
        }
    }

    // Функция обновления мастеров
    function updateMasters(service) {
        masterSelect.innerHTML = '<option value="">Выберите мастера</option>';
        
        const masters = {
            haircut: ['anna'],
            coloring: ['maria'],
            manicure: ['elena']
        };

        const masterNames = {
            anna: 'Анна',
            maria: 'Мария',
            elena: 'Елена'
        };

        if (service && masters[service]) {
            masters[service].forEach(master => {
                const option = document.createElement('option');
                option.value = master;
                option.textContent = masterNames[master];
                masterSelect.appendChild(option);
            });
        }
    }

    // Генерация временных слотов
    function generateTimeSlots() {
        timeSelect.innerHTML = '<option value="">Выберите время</option>';
        const startHour = 9;
        const endHour = 20;
        
        for (let hour = startHour; hour < endHour; hour++) {
            for (let minutes of ['00', '30']) {
                const time = `${hour.toString().padStart(2, '0')}:${minutes}`;
                const option = document.createElement('option');
                option.value = time;
                option.textContent = time;
                timeSelect.appendChild(option);
            }
        }
    }

    // Установка минимальной даты
    function setMinDate() {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        dateInput.min = `${yyyy}-${mm}-${dd}`;
    }

    // Обработчик отправки формы
    bookingForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            service: serviceSelect.value,
            master: masterSelect.value,
            date: dateInput.value,
            time: timeSelect.value,
            name: document.getElementById('name').value,
            phone: document.getElementById('phone').value,
            price: servicePrices[serviceSelect.value]
        };

        alert('Спасибо за запись! Мы свяжемся с вами для подтверждения.');
        this.reset();
        updatePrice('');
    });

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            if (this.getAttribute('href') === '#' && this.hasAttribute('onclick')) {
                return; 
            }
            e.preventDefault();
            const section = document.querySelector(this.getAttribute('href'));
            const headerHeight = document.querySelector('header').offsetHeight;
            const targetPosition = section.offsetTop - headerHeight;
            
            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        });
    });

    // Инициализация
    generateTimeSlots();
    setMinDate();
    updatePrice('');
});