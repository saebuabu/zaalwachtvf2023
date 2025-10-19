import Service from './Service';

export interface DienstNotification {
  userName: string;
  startDienst: string;
  hasShift: boolean;
}

/**
 * Check if user has a shift today
 * @param userName The name of the user to check
 * @returns Promise with notification info
 */
export async function checkUserShiftToday(userName: string): Promise<DienstNotification> {
  if (!userName) {
    return { userName: '', startDienst: '', hasShift: false };
  }

  try {
    const diensten = await Service.getDiensten();
    const today = new Date();
    const todayStr = today.toISOString().split('T')[0]; // Format: 2024-10-20

    // Find shifts for today that match the user's name
    const todayShift = diensten.find((dienst: any) => {
      // Extract date from "2024-10-20 start 14:00" format
      const dienstDate = dienst.StartDienst?.split(' start ')[0];

      const zaalwacht = dienst.Zaalwacht || '';
      const zaalwachtLower = zaalwacht.toLowerCase();
      const userNameLower = userName.toLowerCase();

      // Check if date matches today and name matches user (case insensitive)
      // BUT: skip if there's a '(' before the name (indicating a traded shift)
      if (dienstDate === todayStr && zaalwachtLower.includes(userNameLower)) {
        // Find the position of the username in the Zaalwacht string
        const namePosition = zaalwachtLower.indexOf(userNameLower);

        // Check if there's a '(' before the name (accounting for possible whitespace)
        if (namePosition > 0) {
          const beforeName = zaalwacht.substring(0, namePosition).trim();
          // If the character right before the name (after trimming) is '(', skip this shift
          if (beforeName.endsWith('(')) {
            return false;
          }
        }

        return true;
      }

      return false;
    });

    if (todayShift) {
      return {
        userName,
        startDienst: todayShift.StartDienst,
        hasShift: true
      };
    }

    return { userName, startDienst: '', hasShift: false };
  } catch (error) {
    console.error('Error checking shift:', error);
    return { userName, startDienst: '', hasShift: false };
  }
}

/**
 * Send a notification to the user about their shift
 * @param notification The notification info
 */
export function sendShiftNotification(notification: DienstNotification): void {
  if (!notification.hasShift || !notification.startDienst) {
    return;
  }

  // Extract time from "2024-10-20 start 14:00" format
  const timePart = notification.startDienst.split(' start ')[1] || '';

  if (Notification.permission === 'granted') {
    new Notification('Dienst Vandaag! 🎭', {
      body: `Hoi ${notification.userName}! Je hebt vandaag dienst bij de Verkadefabriek om ${timePart} uur.`,
      icon: '/img/icons/android-chrome-192x192.png',
      badge: '/img/icons/android-chrome-192x192.png',
      tag: 'dienst-reminder',
      requireInteraction: true, // Keeps notification visible until user interacts
    });
  }
}

/**
 * Schedule daily notification check at 10:00
 * This function sets up a daily check and should be called when notifications are enabled
 */
export function scheduleDailyNotifications(): void {
  const now = new Date();
  const target = new Date();
  target.setHours(10, 0, 0, 0); // 10:00 AM

  // If it's already past 10:00 today, schedule for tomorrow
  if (now > target) {
    target.setDate(target.getDate() + 1);
  }

  const timeUntilCheck = target.getTime() - now.getTime();

  console.log(`Next notification check scheduled in ${Math.round(timeUntilCheck / 1000 / 60)} minutes`);

  // Schedule the first check
  setTimeout(() => {
    performDailyCheck();
    // Then schedule daily checks (every 24 hours)
    setInterval(performDailyCheck, 24 * 60 * 60 * 1000);
  }, timeUntilCheck);
}

/**
 * Perform the daily check for shifts and send notification if needed
 */
async function performDailyCheck(): Promise<void> {
  const notificationsEnabled = localStorage.getItem('zaalwacht_notificationsEnabled') === 'true';
  const userName = localStorage.getItem('zaalwacht_userName');

  if (!notificationsEnabled || !userName) {
    console.log('Daily check skipped: notifications disabled or no user name');
    return;
  }

  console.log(`Performing daily shift check for ${userName} at 10:00`);

  const notification = await checkUserShiftToday(userName);

  if (notification.hasShift) {
    console.log(`Sending notification: ${userName} has a shift today`);
    sendShiftNotification(notification);
  } else {
    console.log(`No shift today for ${userName}`);
  }
}

/**
 * Initialize notifications on app startup
 * This should be called in main.ts or App.vue
 */
export function initializeNotifications(): void {
  const notificationsEnabled = localStorage.getItem('zaalwacht_notificationsEnabled') === 'true';

  if (notificationsEnabled && Notification.permission === 'granted') {
    scheduleDailyNotifications();
    console.log('Notification system initialized');
  }
}
